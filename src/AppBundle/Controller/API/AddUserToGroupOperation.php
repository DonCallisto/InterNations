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
class AddUserToGroupOperation
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
     *     name="add_user_to_group",
     *     path="/groups/{id}/users",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=UserGroupDTO::class,
     *         "_api_item_operation_name"="add_user_to_group"
     *     }
     * )
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param UserGroupDTO $data
     *
     * @return JsonResponse
     */
    public function __invoke($data)
    {
        $user = $this->userRepo->find($data->id);
        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => sprintf('User %s not found', $data->id)
            ], Response::HTTP_NOT_FOUND);
        }

        $group = $data->getGroup();
        $result = $group->addUser($user);
        if (!$result) {
           return new JsonResponse(null, Response::HTTP_NOT_MODIFIED);
        }

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}