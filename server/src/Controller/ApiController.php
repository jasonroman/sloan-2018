<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/teams", name="api_teams")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function teams()
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
}
