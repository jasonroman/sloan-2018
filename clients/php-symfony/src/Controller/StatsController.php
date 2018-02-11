<?php

namespace App\Controller;

use GuzzleHttp;
use GuzzleHttp\Client as GuzzleClient;
use JasonRoman\Flot\Flot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{
    /**
     * @var GuzzleClient
     */
    private $guzzle;
    private $flot;

    public function __construct()
    {
        $this->flot = new Flot();

        $this->guzzle = new GuzzleClient([
            'base_uri' => getenv('API_URL'),
            'headers'  => [
                'x-api-username' => getenv('API_USERNAME'),
                'x-api-key'      => getenv('API_KEY'),
            ],
        ]);
    }

    /**
     * @Route("/teams/{sport}/offense/average", name="teams_offense_average")
     * @Method({"GET"})
     *
     * @param string $sport
     * @return Response
     */
    public function teamsAverageOffense(string $sport): Response
    {
        $labels  = [];
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/teams/'.$sport.'/offense');
        $teams    = GuzzleHttp\json_decode($response->getBody(), true);

        foreach ($teams as $team) {
            $ratings[] = $team['average_offense_rating'];
            $labels[]  = $team['city'];
        }

        return $this->render('stats/category.html.twig', [
            'title'    => 'NBA Team Average Offense Ratings',
            'minValue' => $this->getMinValue('NBA', 'offense'),
            'maxValue' => $this->getMaxValue('NBA', 'offense') - 10,
            'labels'   => $labels,
            'data'     => $this->flot->convert($ratings, 'vertical', $isTimeSeries = false),
        ]);
    }

    /**
     * @Route("/teams/{sport}/defense/average", name="teams_defense_average")
     * @Method({"GET"})
     *
     * @param string $sport
     * @return Response
     */
    public function teamsAverageDefense(string $sport): Response
    {
        $labels  = [];
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/teams/'.$sport.'/defense');
        $teams    = GuzzleHttp\json_decode($response->getBody(), true);

        foreach ($teams as $team) {
            $ratings[] = $team['average_defense_rating'];
            $labels[]  = $team['city'];
        }

        return $this->render('stats/category.html.twig', [
            'title'    => 'NBA Team Average Defense Ratings',
            'minValue' => $this->getMinValue('NBA', 'defense') + 5,
            'maxValue' => $this->getMaxValue('NBA', 'defense') - 5,
            'labels'   => $labels,
            'data'     => $this->flot->convert($ratings, 'vertical', $isTimeSeries = false),
        ]);
    }

    /**
     * @Route("/teams/{sport}/ratings/average", name="teams_ratings_average")
     * @Method({"GET"})
     *
     * @param string $sport
     * @return Response
     */
    public function teamsAverageRatings(string $sport): Response
    {
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/teams/'.$sport.'/ratings');
        $teams    = GuzzleHttp\json_decode($response->getBody(), true);

        foreach ($teams as $team) {
            $ratings[] = [$team['average_offense_rating'], $team['average_defense_rating']];
        }

        $ratings     = $this->flot->convert($ratings, 'vertical', $isTimeSeries = false);
        $flotRatings = json_decode($ratings, true);

        for ($i = 0; $i < count($teams); $i++) {
            $flotRatings[$i]['label'] = $teams[$i]['full_name'];
            $flotRatings[$i]['bars']  = ['order' => ($i + 1)];
        }
        dump($flotRatings);

        return $this->render('stats/category_side_by_side.html.twig', [
            'title'     => 'NBA Team Average Ratings',
            'minValue'  => $this->getMinValue(strtoupper($sport), 'offense'),
            'maxValue'  => $this->getMaxValue(strtoupper($sport), 'offense') - 10,
            'labels'    => ['Team Average Offense Rating', 'Team Average Defense Rating'],
            'numSeries' => 2,
            'numValues' => count($teams),
            'data'      => json_encode($flotRatings),
        ]);
    }

    /**
     * @Route("/teams/{id}/offense", name="team_offense")
     * @Method({"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function teamOffense(int $id): Response
    {
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/teams/'.$id.'/offense');
        $team     = GuzzleHttp\json_decode($response->getBody(), true);

        $responseInfo = $this->guzzle->request('GET', '/api/teams/'.$id);
        $teamInfo     = GuzzleHttp\json_decode($responseInfo->getBody(), true);

        foreach ($team['offense_ratings'] as $rating) {
            $ratings[$rating['game_date']] = $rating['rating'];
        }

        return $this->render('stats/dual_time.html.twig', [
            'title'          => $team['full_name'].' Offense Ratings',
            'minValue'       => $this->getMinValue($teamInfo['sport']['abbreviation'], 'offense'),
            'maxValue'       => $this->getMaxValue($teamInfo['sport']['abbreviation'], 'offense'),
            'dataVertical'   => $this->flot->convert($ratings, 'vertical', $isTimeSeries = true),
            'dataHorizontal' => $this->flot->convert($ratings, 'horizonal', $isTimeSeries = true),
        ]);
    }

    /**
     * @Route("/teams/{id}/defense", name="team_defense")
     * @Method({"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function teamDefense(int $id): Response
    {
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/teams/'.$id.'/defense');
        $team     = GuzzleHttp\json_decode($response->getBody(), true);

        $responseInfo = $this->guzzle->request('GET', '/api/teams/'.$id);
        $teamInfo     = GuzzleHttp\json_decode($responseInfo->getBody(), true);

        foreach ($team['defense_ratings'] as $rating) {
            $ratings[$rating['game_date']] = $rating['rating'];
        }

        return $this->render('stats/dual_time.html.twig', [
            'title'          => $team['full_name'].' Defense Ratings',
            'minValue'       => $this->getMinValue($teamInfo['sport']['abbreviation'], 'defense'),
            'maxValue'       => $this->getMaxValue($teamInfo['sport']['abbreviation'], 'defense'),
            'dataVertical'   => $this->flot->convert($ratings, 'vertical', $isTimeSeries = true),
            'dataHorizontal' => $this->flot->convert($ratings, 'horizonal', $isTimeSeries = true),
        ]);
    }

    /**
     * Get the minimum axis value for the given sport abbreviation.
     *
     * @param string $sportAbbreviation
     * @param string $type
     * @return int
     */
    private function getMinValue(string $sportAbbreviation, string $type): int
    {
        if ($sportAbbreviation === 'NBA') {
            return 95;
        } elseif ($sportAbbreviation === 'NFL') {
            return 20;
        }

        return 0;
    }

    /**
     * Get the minimum axis value for the given sport abbreviation.
     *
     * @param string $sportAbbreviation
     * @param string $type
     * @return int
     */
    private function getMaxValue(string $sportAbbreviation, string $type): int
    {
        if ($sportAbbreviation === 'NBA') {
            return ($type === 'offense') ? 125 : 110;
        } elseif ($sportAbbreviation === 'NFL') {
            return 35;
        }

        return 100;
    }
}