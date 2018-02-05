<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/public/api")
 */
class PublicApiController extends Controller
{
    /**
     * @Route("/users", name="public_api_users")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function users(SerializerInterface $serializer)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $jsonUsers = $serializer->serialize(
            $users,
            'json',
            ['groups' => ['public']]
        );

        return JsonResponse::fromJsonString($jsonUsers);
    }

    /**
     * @Route("/users/secret", name="public_api_users_secret")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function usersSecret(SerializerInterface $serializer)
    {
        $users     = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonUsers = $serializer->serialize($users, 'json');

        return JsonResponse::fromJsonString($jsonUsers);
    }
}
