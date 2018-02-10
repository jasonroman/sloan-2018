<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/public/api")
 */
class PublicApiController extends Controller
{
    /**
     * @Route("/users", name="public_api_users")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function users(SerializerInterface $serializer)
    {
        $users     = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonUsers);
    }

    /**
     * @Route("/users/secret", name="public_api_users_secret")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function usersSecret(SerializerInterface $serializer)
    {
        $users     = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonUsers = $serializer->serialize($users, 'json');

        return JsonResponse::fromJsonString($jsonUsers);
    }

    /**
     * @Route("/users/{username}", name="public_api_users_username")
     * @Method({"GET"})
     *
     * @param string $username
     * @return Response
     */
    public function user($username)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByUsername($username);

        if (!$user) {
            throw $this->createNotFoundException('Could not find user');
        }

        return new Response(serialize($user));
    }
}
