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
 * @Route("/api/teams")
 */
class TeamsApiController extends Controller
{
    /**
     * @Route("/", name="api_teams")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function teams(SerializerInterface $serializer)
    {
        /** @var Team[] $teams */
        $teams     = $this->getDoctrine()->getRepository(Team::class)->findAll();
        $jsonTeams = $serializer->serialize($teams, 'json', ['groups' => ['public', 'sport_link']]);

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
     * @Route("/{sport}", name="api_teams_sport_teams")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @return JsonResponse
     */
    public function sportTeams(SerializerInterface $serializer, string $sport)
    {
        /** @var Sport $sport */
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneByAbbreviation($sport);

        if (!$sport) {
            throw $this->createNotFoundException('Sport does not exist');
        }

        $jsonTeams = $serializer->serialize($sport->getTeams(), 'json', ['groups' => ['public', 'sport_link']]);

        return JsonResponse::fromJsonString($jsonTeams);
    }

    /**
     * @Route("/{sport}/{name}", name="api_teams_sport_team")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @param string $name
     * @return JsonResponse
     */
    public function sportTeam(SerializerInterface $serializer, string $sport, string $name)
    {
        /** @var Team $teams */
        $team = $this->getDoctrine()->getRepository(Team::class)
            ->findOneBySportAbbreviationAndCityOrName($sport, $name);

        if (!$team) {
            throw $this->createNotFoundException('Team does not exist');
        }

        $jsonTeam = $serializer->serialize($team, 'json', ['groups' => ['public', 'sport_link']]);

        return JsonResponse::fromJsonString($jsonTeam);
    }
}
