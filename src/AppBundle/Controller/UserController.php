<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\CommandHandler\TokenAwareUserCommandHandler;
use AppBundle\CommandHandler\UserHandlingForbiddenException;
use AppBundle\Entity\User;
use AppBundle\Form\UserRegistrationType;
use AppBundle\Form\UserUpdateType;
use AppBundle\Model\User\UserRegistrationCommand;
use AppBundle\Model\User\UserUpdateCommand;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    const USER_CREATED_MSG = 'User created!';
    const USER_UPDATED_MSG = 'User updated!';
    const USER_DELETED_MSG = 'User deleted!';
    const USER_DELETE_HIMSELF_ERROR_MSG = "A user can't delete itself!";

    const LIST_USERS_ROUTE = 'list-users';

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/register", name="register-user")
     */
    public function register(Request $request, TokenAwareUserCommandHandler $commandHandler)
    {
        $registerCommand = new UserRegistrationCommand();
        $form = $this->createForm(UserRegistrationType::class, $registerCommand);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $commandHandler->register($registerCommand);
            } catch (UserHandlingForbiddenException $e) {
                throw new AccessDeniedException();
            }

            $this->addFlash('success', self::USER_CREATED_MSG);

            return $this->redirectToRoute(self::LIST_USERS_ROUTE);
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list", name="list-users")
     */
    public function list(UserRepository $repo)
    {
        // We should not take advantage of lazy loading so load every field we gonna use but that's a basic example
        return $this->render('user/list.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/update/{id}", name="update-user")
     */
    public function update(Request $request, User $user, TokenAwareUserCommandHandler $commandHandler)
    {
        $updateCommand = UserUpdateCommand::createFromUser($user);
        $form = $this->createForm(UserUpdateType::class, $updateCommand);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $commandHandler->update($user, $updateCommand);
            } catch (UserHandlingForbiddenException $e) {
                throw new AccessDeniedException();
            }

            $this->addFlash('success', self::USER_UPDATED_MSG);

            return $this->redirectToRoute(self::LIST_USERS_ROUTE);
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="delete-user")
     */
    public function delete(User $user, TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $loggedUser = $tokenStorage->getToken()->getUser();
        if ($loggedUser === $user) {
            $this->addFlash('error', self::USER_DELETE_HIMSELF_ERROR_MSG);
        } else {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', self::USER_DELETED_MSG);
        }

        return $this->redirectToRoute(self::LIST_USERS_ROUTE);
    }
}