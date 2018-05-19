<?php

declare(strict_types=1);

namespace InternationsBehat\Context\API\Group;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Repository\GroupRepository;
use AppBundle\Repository\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use InternationsBehat\Context\API\SharedContext;
use InternationsBehat\Context\BaseContext;
use InternationsBehat\Context\GroupTransformer;
use InternationsBehat\Context\UserTransformer;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class AddUserContext extends BaseContext
{
    use UserTransformer;
    use GroupTransformer;

    private $client;

    private $userRepo;

    private $groupRepo;

    private $responseStatus;

    public function __construct(Client $client, UserRepository $userRepository, GroupRepository $groupRepository)
    {
        $this->client = $client;
        $this->userRepo = $userRepository;
        $this->groupRepo = $groupRepository;
    }

    /**
     * @When /^I try to add (user with "(?:[^"]*)" username) to (group with "(?:[^"]*)" name)$/
     */
    public function tryAddUserToGroup(User $user, Group $group)
    {
        $token = SharedContext::get(SharedContext::TOKEN);
        $response = $this->client->post(sprintf('groups/%s/users', $group->getId()), [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            RequestOptions::JSON => [
                'id' => $user->getId()
            ],
            'http_errors' => false,
        ]);

        $this->responseStatus = $response->getStatusCode();
    }

    /**
     * @Then /^(User with "(?:[^"]*)" username) should be added to (group with "(?:[^"]*)" name)$/
     */
    public function userShouldBeAddedToGroup(User $user, Group $group)
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_OK,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );

        $this->getEntityManager()->refresh($group);

        Assert::true($group->getUsers()->contains($user), 'User not added to group');
    }

    /**
     * @Then /^I should not be allowed to add user to the group$/
     */
    public function notAllowedToAddUserToGroup()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_FORBIDDEN,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );
    }

    /**
     * @Then /^I should be aware that operation didn't taken place$/
     */
    public function checkNotModified()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_NOT_MODIFIED,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );
    }

    protected function getUserRepo(): UserRepository
    {
        return $this->userRepo;
    }

    protected function getGroupRepo(): GroupRepository
    {
        return $this->groupRepo;
    }
}