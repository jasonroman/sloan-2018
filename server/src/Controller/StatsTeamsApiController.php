<?php

namespace App\Controller;

use App\Entity\Sport;
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
     * @Route("/{sport}/ratings", name="api_stats_teams_ratings", requirements={"sport"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @return JsonResponse
     */
    public function teamsRatings(SerializerInterface $serializer, string $sport): JsonResponse
    {
        return $this->getTeams($sport, $serializer, ['public', 'offense_ratings_assoc', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{sport}/offense", name="api_stats_teams_offense_ratings", requirements={"sport"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @return JsonResponse
     */
    public function teamsOffenseRatings(SerializerInterface $serializer, string $sport): JsonResponse
    {
        return $this->getTeams($sport, $serializer, ['public', 'offense_ratings_assoc']);
    }

    /**
     * @Route("/{sport}/defense", name="api_stats_teams_defense_ratings", requirements={"sport"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @return JsonResponse
     */
    public function teamsDefenseRatings(SerializerInterface $serializer, string $sport): JsonResponse
    {
        return $this->getTeams($sport, $serializer, ['public', 'defense_ratings_assoc']);
    }

    /**
     * @Route("/{id}/ratings", name="api_stats_teams_team_ratings", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teamRatings(SerializerInterface $serializer, int $id): JsonResponse
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
    public function teamOffenseRatings(SerializerInterface $serializer, int $id): JsonResponse
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
    public function teamDefenseRatings(SerializerInterface $serializer, int $id): JsonResponse
    {
        return $this->getTeam($id, $serializer, ['public', 'defense_ratings_assoc']);
    }

    /**
     * Get all teams, and restrict particular results based on the passed in groups
     *
     * @param string $sport
     * @param SerializerInterface $serializer
     * @param array $groups
     * @return JsonResponse
     */
    private function getTeams(string $sport, SerializerInterface $serializer, array $groups = ['public']): JsonResponse
    {
        /** @var Team[] $teams */
        $teams     = $this->getDoctrine()->getRepository(Team::class)->findAllBySport($sport);
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
