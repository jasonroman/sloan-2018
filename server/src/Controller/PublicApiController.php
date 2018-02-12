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
        /** @var User[] $user */
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
        /** @var User[] $user */
        $users     = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonUsers = $serializer->serialize($users, 'json');

        return JsonResponse::fromJsonString($jsonUsers);
    }

    /**
     * @Route("/users/{id}", name="public_api_users_user", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param int $id
     * @return JsonResponse
     */
    public function user(SerializerInterface $serializer, $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Could not find user');
        }

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonUser);
    }

    /**
     * @Route("/users/{id}/serialize", name="public_api_users_user_serialize", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function userSerialize($id): Response
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Could not find user');
        }

        return new Response(serialize($user));
    }
}
