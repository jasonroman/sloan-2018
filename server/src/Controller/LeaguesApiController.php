<?php

namespace App\Controller;

use App\Entity\League;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/leagues")
 */
class LeaguesApiController extends Controller
{
    /**
     * @Route("/list", name="api_leagues")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function leagues(SerializerInterface $serializer)
    {
        /** @var Leagues[] $leagues */
        $leagues     = $this->getDoctrine()->getRepository(League::class)->findAll();
        $jsonLeagues = $serializer->serialize($leagues, 'json', ['groups' => ['public', 'teams_assoc']]);

        return JsonResponse::fromJsonString($jsonLeagues);
    }

    /**
     * @Route("/no-associations", name="api_leagues_no_associations")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function leaguesNoAssociations(SerializerInterface $serializer)
    {
        /** @var League[] $teams */
        $leagues      = $this->getDoctrine()->getRepository(League::class)->findAll();
        $jsonLeagues = $serializer->serialize($leagues, 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonLeagues);
    }

    /**
     * @Route("/hardcoded-example", name="api_hardcoded_example")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function hardcodedExample()
    {
        return new JsonResponse([
            [
                'id'        => 1,
                'city'      => 'Houston',
                'team_name' => 'Rockets',
            ],
            [
                'id'        => 2,
                'city'      => 'Detroit',
                'team_name' => 'Pistons',
            ],
            [
                'id'        => 3,
                'city'      => 'Boston',
                'team_name' => 'Celtics',
            ],
        ]);
    }

    /**
     * @Route("/{league}", name="api_leagues_league", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $league
     * @return JsonResponse
     */
    public function league(SerializerInterface $serializer, $league)
    {
        /** @var League $league */
        $league = $this->getDoctrine()->getRepository(League::class)->findOneByAbbreviation($league);

        if (!$league) {
            throw $this->createNotFoundException('League does not exist');
        }

        $jsonLeague = $serializer->serialize($league, 'json', ['groups' => ['public', 'teams_assoc']]);

        return JsonResponse::fromJsonString($jsonLeague);
    }

    /**
     * @Route("/{league}/teams", name="api_leagues_league_teams", requirements={"league"="[a-z]+"})
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

        $jsonLeague = $serializer->serialize($league->getTeams(), 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonLeague);
    }
}
