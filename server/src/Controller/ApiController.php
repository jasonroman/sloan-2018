<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/teams", name="api_teams")
     */
    public function teams()
    {
        return new JsonResponse([
            [
                'id' => 1,
                'city' => 'Houston',
                'team_name' => 'Rockets',
            ],
            [
                'id' => 2,
                'city' => 'Detroit',
                'team_name' => 'Pistons',
            ],
            [
                'id' => 3,
                'city' => 'Boston',
                'team_name' => 'Celtics',
            ],
        ]);
    }
}
