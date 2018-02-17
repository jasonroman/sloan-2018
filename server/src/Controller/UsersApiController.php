<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api")
 */
class UsersApiController extends Controller
{
    // default sloan users that cannot be modified or deleted
    const DEFAULT_SLOAN_USERS = ['jason', 'sloan'];

    /**
     * @Route("/users", name="api_users_list")
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
     * @Route("/users", name="api_users_add")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        /** Get the currently logged in user @var User $securityUser */
        $securityUser = $this->getUser();

        // get the username from the passed-in value ; do not go past 50-character limit
        $username = substr($request->get('username'), 0, 50);

        if (!strlen($username)) {
            throw new BadRequestHttpException('Username cannot be empty');
        }

        // if user is not an admin, do not allow adding a new user
        if (!$securityUser->isAdmin()) {
            throw $this->createAccessDeniedException('Only admins have access to add new users');
        }

        // throw error if trying to add a username that already exists
        if ($this->getDoctrine()->getRepository(User::class)->findOneByUsername($username)) {
            throw new BadRequestHttpException('Username already exists');
        }

        // create the new user entity (api key will automatically generated) and persist to the database
        $user = new User($username);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        // return the newly-created user and the location where that user can be viewed
        return $this->json(
            $user,
            Response::HTTP_CREATED, [
            'Location' => $this->generateUrl(
                'public_api_users_user',
                ['id' => $user->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ],
            ['groups' => ['public', 'apiKey']]
        );
    }

    /**
     * @Route("/users/{id}", name="api_users_update", requirements={"id"="\d+"})
     * @Method({"PATCH"})
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        // get the currently logged in user, and throw an error if the requested user id does not exist

        /** @var User $securityUser */
        $securityUser = $this->getUser();

        /** @var User $user */
        if (!($user = $this->getDoctrine()->getRepository(User::class)->find($id))) {
            throw new BadRequestHttpException('User does not exist');
        }

        // the content comes in as JSON data; convert to an array
        $updates = json_decode($request->getContent(), true);

        // if user is not a super admin or is not updating themselves, do not allow updating the user
        if (!($securityUser->isAdmin() || $securityUser->getUsername() === $user->getUsername())) {
            return $this->json(
                ['error' => 'If you are not a super admin you may only update yourself'],
                Response::HTTP_FORBIDDEN
            );
        }

        // do not allow updating the default sloan users unless super admin
        if (in_array($user->getUsername(), self::DEFAULT_SLOAN_USERS) && !$securityUser->isSuperAdmin()) {
            return $this->json(['error' => 'Cannot update the default Sloan users'], Response::HTTP_BAD_REQUEST);
        }

        // check that an email address was entered and meets the proper criteria
        if (!isset($updates['email']) || strlen($updates['email']) < 5 || strlen($updates['email']) > 64) {
            throw new BadRequestHttpException('Email address must be between 5 and 64 characters');
        }

        // update the user's email address
        $user->setEmail($updates['email']);
        $this->getDoctrine()->getManager()->flush();

        // return the updated user
        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    /**
     * @Route("/users/{id}", name="api_users_delete", requirements={"id"="\d+"})
     * @Method({"DELETE"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        // get the currently logged in user, and throw an error if the requested user id does not exist

        /** @var User $securityUser */
        $securityUser = $this->getUser();

        /** @var User $user */
        if (!($user = $this->getDoctrine()->getRepository(User::class)->find($id))) {
            throw new BadRequestHttpException('User does not exist');
        }

        // if user is not a super admin or is not deleting themselves, do not allow deleting the user
        if (!($securityUser->isAdmin() || $securityUser->getUsername() === $user->getUsername())) {
            throw $this->createAccessDeniedException('If you are not a super admin you may only delete yourself');
        }

        // do not allow deleting the default sloan users
        if (in_array($user->getUsername(), self::DEFAULT_SLOAN_USERS)) {
            throw new BadRequestHttpException('Cannot delete the default Sloan users');
        }

        // delete the user
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        // return an empty successful response
        return $this->json(['success' => true]);
    }
}
