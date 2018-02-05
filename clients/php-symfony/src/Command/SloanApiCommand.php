<?php

namespace App\Command;

use GuzzleHttp;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;

class SloanApiCommand extends Command
{
    /**
     * @var GuzzleClient
     */
    private $guzzle;

    /**
     * @var string
     */
    protected static $defaultName = 'sloan:api';

    /**
     * Create a new guzzle client that can send http requests to the api.
     * This sets the base uri as well as the user credentials that are necessary for accessing the api.
     */
    public function __construct()
    {
        // create a new guzzle client that can send http requests to our apiusing the base uri and user credentials
        $this->guzzle = new GuzzleClient([
            'base_uri' => getenv('API_URL'),
            'headers' => [
                'x-api-username' => getenv('API_USERNAME'),
                'x-api-key'      => getenv('API_KEY'),
            ],
        ]);

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Retrieve information from the jasonroman/sloan-2018 api')
            ->addArgument('endpoint', InputArgument::REQUIRED, 'URL/endpoint to retrieve')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                "Format to display ('raw' or 'array') - default is 'raw' json string, 'array' is PHP array"
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io       = new SymfonyStyle($input, $output);
        $endpoint = $input->getArgument('endpoint');

        try {
            // retrieve the JSON response from the api and convert it to a PHP array
            $response      = $this->guzzle->request('GET', $endpoint);
            $responseArray = GuzzleHttp\json_decode($response->getBody(), true);

            // get the response display format - raw or array; anything not specified as array will return as raw
            $format = ($input->getOption('format') ?? 'raw');

            // print the result to the console either as a raw JSON string or a pretty-printed PHP array
            $responseString = ($format !== 'array')
                ? json_encode($responseArray)
                : print_r($responseArray, true)
            ;

            $io->success($responseString);

        } catch (ClientException | ServerException $e) {
            $this->handleException($endpoint, $io, $e);
        }
    }

    /**
     * Handle an invalid response from the api request.
     *
     * @param string $endpoint
     * @param SymfonyStyle $io
     * @param \Exception $e
     */
    private function handleException(string $endpoint, SymfonyStyle $io, \Exception $e)
    {
        $error = (string) $e->getCode().' - ';

        if ($e->getCode() === Response::HTTP_NOT_FOUND) {
            $io->error($error.'No api endpoint exists at '.$endpoint);
        } elseif ($e->getCode() === Response::HTTP_FORBIDDEN) {
            $io->error($error.'Invalid credentials');
        } else {
            $io->error($error.'Something really bad happened');
        }
    }
}
