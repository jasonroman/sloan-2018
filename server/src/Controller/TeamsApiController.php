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
 * @Route("/api/teams")
 */
class TeamsApiController extends Controller
{
    /**
     * @Route("/list", name="api_teams")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teams(SerializerInterface $serializer)
    {
        /** @var Team[] $teams */
        $teams     = $this->getDoctrine()->getRepository(Team::class)->findAll();
        $jsonTeams = $serializer->serialize($teams, 'json', ['groups' => ['public', 'league_assoc']]);

        return JsonResponse::fromJsonString($jsonTeams);
    }

    /**
     * @Route("/no-associations", name="api_teams_no_associations")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teamsNoAssociations(SerializerInterface $serializer)
    {
        /** @var Team[] $teams */
        $teams     = $this->getDoctrine()->getRepository(Team::class)->findAll();
        $jsonTeams = $serializer->serialize($teams, 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonTeams);
    }

    /**
     * @Route("/{id}", name="api_teams_team", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param int $id
     * @return JsonResponse
     */
    public function team(SerializerInterface $serializer, $id): JsonResponse
    {
        /** @var Team $team */
        $team     = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $jsonTeam = $serializer->serialize($team, 'json', ['groups' => ['public', 'league_assoc']]);

        return JsonResponse::fromJsonString($jsonTeam);
    }

    /**
     * @Route("/{league}", name="api_teams_league_teams", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $league
     * @return JsonResponse
     */
    public function leagueTeams(SerializerInterface $serializer, $league)
    {
        /** @var League $league */
        $league = $this->getDoctrine()->getRepository(League::class)->findOneByAbbreviation($league);

        if (!$league) {
            throw $this->createNotFoundException('League does not exist');
        }

        $jsonTeams = $serializer->serialize($league->getTeams(), 'json', ['groups' => ['public', 'league_assoc']]);

        return JsonResponse::fromJsonString($jsonTeams);
    }

    /**
     * @Route("/{league}/{name}", name="api_teams_league_team", requirements={"league"="[a-z]+", "name"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $league
     * @param string $name
     * @return JsonResponse
     */
    public function leagueTeam(SerializerInterface $serializer, $league, $name)
    {
        /** @var Team $teams */
        $team = $this->getDoctrine()->getRepository(Team::class)
            ->findOneByLeagueAbbreviationAndCityOrName($league, $name);

        if (!$team) {
            throw $this->createNotFoundException('Team does not exist');
        }

        $jsonTeam = $serializer->serialize($team, 'json', ['groups' => ['public', 'league_assoc']]);

        return JsonResponse::fromJsonString($jsonTeam);
    }
}
