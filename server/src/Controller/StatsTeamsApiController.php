<?php

namespace App\Controller;

use App\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/stats/teams")
 */
class StatsTeamsApiController extends Controller
{
    /**
     * @Route("/{id}/ratings", name="api_stats_teams_team_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function teamRatings($id): JsonResponse
    {
        return $this->getTeam($id, ['public', 'offense_ratings_assoc', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{id}/offense", name="api_stats_teams_team_offense_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function teamOffenseRatings($id): JsonResponse
    {
        return $this->getTeam($id, ['public', 'offense_ratings_assoc']);
    }

    /**
     * @Route("/{id}/defense", name="api_stats_teams_team_defense_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function teamDefenseRatings($id): JsonResponse
    {
        return $this->getTeam($id, ['public', 'defense_ratings_assoc']);
    }

    /**
     * Get a team, and restrict particular results based on the passed in groups
     *
     * @param int $id
     * @param array $groups
     * @return JsonResponse
     */
    private function getTeam(int $id, array $groups = ['public']): JsonResponse
    {
        /** @var Team $team */
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Team does not exist');
        }

        return $this->json($team, Response::HTTP_OK, [], ['groups' => $groups]);
    }
}
