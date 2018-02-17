<?php

namespace App\Controller;

use App\Form\LeagueRatingsType;
use App\Form\TeamRatingsType;
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
     * @Route("/", name="index")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('stats/index.html.twig', [
            'title' => 'League/Team Ratings',
        ]);
    }

    /**
     * @Route("/teams/{league}/offense", name="teams_offense", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return Response
     */
    public function teamsOffense($league): Response
    {
        $labels  = [];
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/league-teams/'.$league.'/offense');
        $teams    = GuzzleHttp\json_decode($response->getBody(), true);

        foreach ($teams as $team) {
            $ratings[] = $team['average_offense_rating'];
            $labels[]  = $team['city'];
        }

        return $this->render('stats/category.html.twig', [
            'title'    => strtoupper($league).' Team Offense Ratings',
            'minValue' => $this->getMinValue($league, 'offense'),
            'maxValue' => $this->getMaxValue($league, 'offense') - 10,
            'labels'   => $labels,
            'data'     => $this->flot->convert($ratings, 'vertical', $isTimeSeries = false),
        ]);
    }

    /**
     * @Route("/teams/{league}/defense", name="teams_defense", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return Response
     */
    public function teamsDefense($league): Response
    {
        $labels  = [];
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/league-teams/'.$league.'/defense');
        $teams    = GuzzleHttp\json_decode($response->getBody(), true);

        foreach ($teams as $team) {
            $ratings[] = $team['average_defense_rating'];
            $labels[]  = $team['city'];
        }

        return $this->render('stats/category.html.twig', [
            'title'    => strtoupper($league).' Team Defense Ratings',
            'minValue' => $this->getMinValue($league, 'defense') + 5,
            'maxValue' => $this->getMaxValue($league, 'defense') - 5,
            'labels'   => $labels,
            'data'     => $this->flot->convert($ratings, 'vertical', $isTimeSeries = false),
        ]);
    }

    /**
     * @Route("/teams/{league}/ratings", name="teams_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return Response
     */
    public function teamsRatings($league): Response
    {
        $ratings = [];

        $response = $this->guzzle->request('GET', '/api/stats/league-teams/'.$league.'/ratings');
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

        return $this->render('stats/category_side_by_side.html.twig', [
            'title'     => strtoupper($league).' Team Ratings',
            'minValue'  => $this->getMinValue(strtoupper($league), 'offense'),
            'maxValue'  => $this->getMaxValue(strtoupper($league), 'offense') - 10,
            'labels'    => ['Team Offense Rating', 'Team Defense Rating'],
            'numSeries' => 2,
            'numValues' => count($teams),
            'data'      => json_encode($flotRatings),
        ]);
    }

    /**
     * @Route("/teams/{id}/offense", name="team_offense", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function teamOffense($id): Response
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
            'minValue'       => $this->getMinValue($teamInfo['league']['abbreviation'], 'offense'),
            'maxValue'       => $this->getMaxValue($teamInfo['league']['abbreviation'], 'offense'),
            'dataVertical'   => $this->flot->convert($ratings, 'vertical', $isTimeSeries = true),
            'dataHorizontal' => $this->flot->convert($ratings, 'horizonal', $isTimeSeries = true),
        ]);
    }

    /**
     * @Route("/teams/{id}/defense", name="team_defense", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function teamDefense($id): Response
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
            'minValue'       => $this->getMinValue($teamInfo['league']['abbreviation'], 'defense'),
            'maxValue'       => $this->getMaxValue($teamInfo['league']['abbreviation'], 'defense'),
            'dataVertical'   => $this->flot->convert($ratings, 'vertical', $isTimeSeries = true),
            'dataHorizontal' => $this->flot->convert($ratings, 'horizonal', $isTimeSeries = true),
        ]);
    }

    /**
     * Make sure each page has the forms to submit.
     *
     * {@inheritdoc}
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $leagueResponseInfo = $this->guzzle->request('GET', '/api/leagues');
        $leagues            = GuzzleHttp\json_decode($leagueResponseInfo->getBody(), true);

        $teamResponseInfo = $this->guzzle->request('GET', '/api/teams');
        $teams            = GuzzleHttp\json_decode($teamResponseInfo->getBody(), true);

        $leagueChoices = [];
        $teamChoices   = [];

        foreach ($leagues as $league) {
            $leagueChoices[strtoupper($league['abbreviation'])] = strtolower($league['abbreviation']);
        }

        foreach ($teams as $team) {
            $teamChoices[($team['full_name'])] = $team['id'];
        }

        $leagueRatingsForm = $this->createForm(LeagueRatingsType::class, null, ['league_choices' => $leagueChoices]);
        $teamRatingsForm   = $this->createForm(TeamRatingsType::class, null, ['team_choices' => $teamChoices]);

        $parameters['league_ratings_form'] = $leagueRatingsForm->createView();
        $parameters['team_ratings_form']   = $teamRatingsForm->createView();

        return parent::render($view, $parameters);
    }

    /**
     * Get the minimum axis value for the given league abbreviation.
     *
     * @param string $leagueAbbreviation
     * @param string $type
     * @return int
     */
    private function getMinValue(string $leagueAbbreviation, string $type): int
    {
        $leagueAbbreviation = strtolower($leagueAbbreviation);

        if ($leagueAbbreviation === 'nba') {
            return 95;
        } elseif ($leagueAbbreviation === 'nfl') {
            return 20;
        }

        return 0;
    }

    /**
     * Get the minimum axis value for the given league abbreviation.
     *
     * @param string $leagueAbbreviation
     * @param string $type
     * @return int
     */
    private function getMaxValue(string $leagueAbbreviation, string $type): int
    {
        $leagueAbbreviation = strtolower($leagueAbbreviation);

        if ($leagueAbbreviation === 'nba') {
            return ($type === 'offense') ? 125 : 110;
        } elseif ($leagueAbbreviation === 'nfl') {
            return 45;
        }

        return 100;
    }
}