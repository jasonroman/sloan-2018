<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/")
 */
class MainController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return new Response('Visit /api/teams and /public/api/users');
    }
}
