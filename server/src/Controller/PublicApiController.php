<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/public/api")
 */
class PublicApiController extends Controller
{
    /**
     * @Route("/users", name="public_api_users")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        /** @var User[] $user */
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->json($users, Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    /**
     * @Route("/users/secret", name="public_api_users_secret")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function usersSecret(): JsonResponse
    {
        /** @var User[] $user */
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->json($users);
    }

    /**
     * @Route("/users/{id}", name="public_api_users_user", requirements={"id"="\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function user($id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Could not find user');
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['public']]);
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
