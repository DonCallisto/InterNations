<?php

declare(strict_types=1);

namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\API\DTO\UserGroupDTO;

/**
 * @Route("/api")
 */
class RemoveUserFromGroupOperation
{
    private $userRepo;

    private $em;

    public function __construct(UserRepository $userRepo, EntityManagerInterface $em)
    {
        $this->userRepo = $userRepo;
        $this->em = $em;
    }

    /**
     * @Route(
     *     name="remove_user_from_group",
     *     path="/groups/{id}/users/{userId}",
     *     methods={"DELETE"},
     *     defaults={
     *         "_api_resource_class"=UserGroupDTO::class,
     *         "_api_item_operation_name"="remove_user_from_group"
     *     }
     * )
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param UserGroupDTO $data
     * @param string $userId
     *
     * @return JsonResponse
     */
    public function __invoke($data, $userId)
    {
        $user = $this->userRepo->find($userId);
        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => sprintf('User %s not found', $userId)
            ], Response::HTTP_NOT_FOUND);
        }

        $group = $data->getGroup();
        $result = $group->removeUser($user);
        if (!$result) {
            return new JsonResponse([
                'message' => sprintf('User %s is not a member of %s group', $userId, $group->getId())
            ], Response::HTTP_NOT_FOUND);
        }

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}