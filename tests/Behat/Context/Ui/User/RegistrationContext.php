<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui\User;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use InternationsBehat\Context\BaseContext;
use InternationsBehat\Context\UserTransformer;
use InternationsBehat\Page\User\ListPage;
use InternationsBehat\Page\User\RegisterPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;
use Webmozart\Assert\Assert;

class RegistrationContext extends BaseContext
{
    use UserTransformer;

    const NEW_USER_USERNAME = 'username';

    private $registerPage;

    private $listPage;

    private $userRepo;

    public function __construct(RegisterPage $registerPage, ListPage $listPage, UserRepository $userRepository)
    {
        $this->registerPage = $registerPage;
        $this->listPage = $listPage;
        $this->userRepo = $userRepository;
    }

    /**
     * @Given /^I try to create a user$/
     */
    public function tryUserRegistration()
    {
        try {
            $this->registerPage->open();
            $this->registerPage->registerUser(self::NEW_USER_USERNAME, 'email@email.foo', 'password', 'Samuele');
        } catch (UnexpectedPageException $e) {
        }
    }

    /**
     * @Then /^User should be created$/
     */
    public function userShouldBeCreated()
    {
        $user = $this->fromUsernameToUser(self::NEW_USER_USERNAME);

        Assert::true($this->listPage->isOpen(), 'User list page is not open');
        Assert::isInstanceOf($user, User::class, 'User not created!');
        Assert::true($this->listPage->isUserCreationMessageShown(), 'User message not shown');
    }

    /**
     * @Then /^I should not be allowed to create user$/
     */
    public function userShouldNotBeAllowedToBeCreated()
    {
        Assert::eq($this->getSession()->getStatusCode(), 403, 'User creation enabled!');
    }

    protected function getUserRepo(): UserRepository
    {
        return $this->userRepo;
    }
}