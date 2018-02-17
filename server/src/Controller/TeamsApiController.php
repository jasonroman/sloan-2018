<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api")
 */
class TeamsApiController extends Controller
{
    /**
     * @Route("/teams", name="api_teams_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function teams(): JsonResponse
    {
        /** @var Team[] $teams */
        $teams = $this->getDoctrine()->getRepository(Team::class)->findAll();

        return $this->json($teams, Response::HTTP_OK, [], ['groups' => ['public', 'league_assoc']]);
    }

    /**
     * @Route("/teams/no-associations", name="api_teams_no_associations")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function teamsNoAssociations(): JsonResponse
    {
        /** @var Team[] $teams */
        $teams = $this->getDoctrine()->getRepository(Team::class)->findAll();

        return $this->json($teams, Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    /**
     * @Route("/teams/{id}", name="api_teams_team", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function team($id): JsonResponse
    {
        /** @var Team $team */
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Team does not exist');
        }

        return $this->json($team, Response::HTTP_OK, [], ['groups' => ['public', 'league_assoc']]);
    }

    /**
     * @Route("/teams/{league}", name="api_teams_league_teams", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return JsonResponse
     */
    public function leagueTeams($league): JsonResponse
    {
        /** @var League $league */
        $league = $this->getDoctrine()->getRepository(League::class)->findOneByAbbreviation($league);

        if (!$league) {
            throw $this->createNotFoundException('League does not exist');
        }

        return $this->json($league->getTeams(), Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    /**
     * @Route("/teams/{league}/{name}", name="api_teams_league_team", requirements={"league"="[a-z]+", "name"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @param string $name
     * @return JsonResponse
     */
    public function leagueTeam($league, $name): JsonResponse
    {
        /** @var Team $team */
        $team = $this->getDoctrine()->getRepository(Team::class)
            ->findOneByLeagueAbbreviationAndCityOrName($league, $name);

        if (!$team) {
            throw $this->createNotFoundException('Team does not exist');
        }

        return $this->json($team, Response::HTTP_OK, [], ['groups' => ['public', 'league_assoc']]);
    }
}
