<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\CommandHandler\GroupHandlingForbiddenException;
use AppBundle\CommandHandler\GroupNotFoundException;
use AppBundle\CommandHandler\TokenAwareGroupDeleteHandler;
use AppBundle\CommandHandler\TokenAwareGroupPersisterHandler;
use AppBundle\Form\GroupCreationType;
use AppBundle\Model\Group\GroupCreationCommand;
use AppBundle\Model\Group\GroupDeleteCommand;
use AppBundle\Repository\GroupRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/group")
 */
class GroupController extends Controller
{
    const GROUP_CREATED_MSG = 'Group created!';
    const GROUP_NOT_FOUND_MSG = 'Group not found!';
    const GROUP_DELETED_MSG = 'Group delete!';
    const GROUP_WITH_USERS_MSG = 'Cannot delete group as at least one user in it';

    const LIST_GROUPS_ROUTE = 'list-groups';

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/create", name="create-group")
     */
    public function create(Request $request, TokenAwareGroupPersisterHandler $commandHandler)
    {
        $creationCommand = new GroupCreationCommand();
        $form = $this->createForm(GroupCreationType::class, $creationCommand);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $commandHandler->create($creationCommand);
            } catch (GroupHandlingForbiddenException $e) {
                throw new AccessDeniedException();
            }

            $this->addFlash('success', self::GROUP_CREATED_MSG);

            return $this->redirectToRoute(self::LIST_GROUPS_ROUTE);
        }

        return $this->render('group/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list", name="list-groups")
     */
    public function list(GroupRepository $repo)
    {
        // We should not take advantage of lazy loading so load every field we gonna use but that's a basic example
        return $this->render('group/list.html.twig', [
            'groups' => $repo->findAll(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="delete-group")
     */
    public function delete(string $id, TokenAwareGroupDeleteHandler $commandHandler)
    {
        $command = new GroupDeleteCommand($id);

        try {
            $result = $commandHandler->delete($command);
        } catch (GroupHandlingForbiddenException $e) {
            throw new AccessDeniedException();
        } catch (GroupNotFoundException $e) {
            $this->addFlash('error', self::GROUP_NOT_FOUND_MSG);

            return $this->redirectToRoute(self::LIST_GROUPS_ROUTE);
        }

        if ($result) {
            $this->addFlash('success', self::GROUP_DELETED_MSG);
        } else {
            $this->addFlash('error', self::GROUP_WITH_USERS_MSG);
        }

        return $this->redirectToRoute(self::LIST_GROUPS_ROUTE);

    }
}