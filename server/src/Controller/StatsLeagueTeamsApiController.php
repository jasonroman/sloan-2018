<?php

namespace App\Controller;

use App\Entity\League;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/stats/league-teams")
 */
class StatsLeagueTeamsApiController extends Controller
{
    /**
     * @Route("/{league}/ratings", name="api_stats_teams_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return JsonResponse
     */
    public function teamsRatings($league): JsonResponse
    {
        return $this->getTeams($league, ['public', 'offense_ratings_assoc', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{league}/offense", name="api_stats_teams_offense_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return JsonResponse
     */
    public function teamsOffenseRatings($league): JsonResponse
    {
        return $this->getTeams($league, ['public', 'offense_ratings_assoc']);
    }

    /**
     * @Route("/{league}/defense", name="api_stats_teams_defense_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return JsonResponse
     */
    public function teamsDefenseRatings($league): JsonResponse
    {
        return $this->getTeams($league, ['public', 'defense_ratings_assoc']);
    }

    /**
     * Get all teams, and restrict particular results based on the passed in groups
     *
     * @param string $league
     * @param array $groups
     * @return JsonResponse
     */
    private function getTeams(string $league, array $groups = ['public']): JsonResponse
    {
        /** @var League $league */
        $league = $this->getDoctrine()->getRepository(League::class)->findOneByAbbreviation($league);

        if (!$league) {
            throw $this->createNotFoundException('League does not exist');
        }

        return $this->json($league->getTeams(), Response::HTTP_OK, [], ['groups' => $groups]);
    }
}
