<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/stats/teams")
 */
class StatsTeamsApiController extends Controller
{
    /**
     * @Route("/{league}/ratings", name="api_stats_teams_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $league
     * @return JsonResponse
     */
    public function teamsRatings(SerializerInterface $serializer, $league): JsonResponse
    {
        return $this->getTeams($league, $serializer, ['public', 'offense_ratings_assoc', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{league}/offense", name="api_stats_teams_offense_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $league
     * @return JsonResponse
     */
    public function teamsOffenseRatings(SerializerInterface $serializer, $league): JsonResponse
    {
        return $this->getTeams($league, $serializer, ['public', 'offense_ratings_assoc']);
    }

    /**
     * @Route("/{league}/defense", name="api_stats_teams_defense_ratings", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $league
     * @return JsonResponse
     */
    public function teamsDefenseRatings(SerializerInterface $serializer, $league): JsonResponse
    {
        return $this->getTeams($league, $serializer, ['public', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{id}/ratings", name="api_stats_teams_team_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teamRatings(SerializerInterface $serializer, $id): JsonResponse
    {
        return $this->getTeam($id, $serializer, ['public', 'offense_ratings_assoc', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{id}/offense", name="api_stats_teams_team_offense_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teamOffenseRatings(SerializerInterface $serializer, $id): JsonResponse
    {
        return $this->getTeam($id, $serializer, ['public', 'offense_ratings_assoc']);
    }

    /**
     * @Route("/{id}/defense", name="api_stats_teams_team_defense_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teamDefenseRatings(SerializerInterface $serializer, $id): JsonResponse
    {
        return $this->getTeam($id, $serializer, ['public', 'defense_ratings_assoc']);
    }

    /**
     * Get all teams, and restrict particular results based on the passed in groups
     *
     * @param string $league
     * @param SerializerInterface $serializer
     * @param array $groups
     * @return JsonResponse
     */
    private function getTeams(string $league, SerializerInterface $serializer, array $groups = ['public']): JsonResponse
    {
        /** @var Team[] $teams */
        $teams     = $this->getDoctrine()->getRepository(Team::class)->findAllByLeague($league);
        $jsonTeams = $serializer->serialize($teams, 'json', ['groups' => $groups]);

        return JsonResponse::fromJsonString($jsonTeams);
    }

    /**
     * Get a team, and restrict particular results based on the passed in groups
     *
     * @param int $id
     * @param SerializerInterface $serializer
     * @param array $groups
     * @return JsonResponse
     */
    private function getTeam(int $id, SerializerInterface $serializer, array $groups = ['public']): JsonResponse
    {
        /** @var Team $team */
        $team     = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $jsonTeam = $serializer->serialize($team, 'json', ['groups' => $groups]);

        return JsonResponse::fromJsonString($jsonTeam);
    }
}
