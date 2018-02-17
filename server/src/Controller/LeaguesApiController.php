<?php

namespace App\Controller;

use App\Entity\League;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api")
 */
class LeaguesApiController extends Controller
{
    /**
     * @Route("/leagues", name="api_leagues_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function leagues()
    {
        /** @var Leagues[] $leagues */
        $leagues = $this->getDoctrine()->getRepository(League::class)->findAll();

        return $this->json($leagues, Response::HTTP_OK, [], ['groups' => ['public', 'teams_assoc']]);
    }

    /**
     * @Route("/leagues/no-associations", name="api_leagues_no_associations")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function leaguesNoAssociations()
    {
        /** @var League[] $teams */
        $leagues = $this->getDoctrine()->getRepository(League::class)->findAll();

        return $this->json($leagues, Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    /**
     * @Route("/leagues/hardcoded-example", name="api_hardcoded_example")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function hardcodedExample()
    {
        return $this->json([
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
     * @Route("/leagues/{league}", name="api_leagues_league", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return JsonResponse
     */
    public function league($league)
    {
        /** @var League $league */
        $league = $this->getDoctrine()->getRepository(League::class)->findOneByAbbreviation($league);

        if (!$league) {
            throw $this->createNotFoundException('League does not exist');
        }

        return $this->json($league, Response::HTTP_OK, [], ['groups' => ['public', 'teams_assoc']]);
    }

    /**
     * @Route("/leagues/{league}/teams", name="api_leagues_league_teams", requirements={"league"="[a-z]+"})
     * @Method({"GET"})
     *
     * @param string $league
     * @return JsonResponse
     */
    public function leagueTeams($league)
    {
        /** @var League $league */
        $league = $this->getDoctrine()->getRepository(League::class)->findOneByAbbreviation($league);

        if (!$league) {
            throw $this->createNotFoundException('League does not exist');
        }

        return $this->json($league->getTeams(), Response::HTTP_OK, [], ['groups' => ['public']]);
    }
}
