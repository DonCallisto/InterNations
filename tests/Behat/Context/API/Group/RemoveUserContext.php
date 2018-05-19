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

class RemoveUserContext extends BaseContext
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
     * @When /^I try to remove (user with "(?:[^"]*)" username) from (group with "(?:[^"]*)" name)$/
     */
    public function tryRemoveUserFromGroup(User $user, Group $group)
    {
        $token = SharedContext::get(SharedContext::TOKEN);
        $response = $this->client->delete(sprintf('groups/%s/users/%s', $group->getId(), $user->getId()), [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            'http_errors' => false,
        ]);

        $this->responseStatus = $response->getStatusCode();
    }

    /**
     * @Then /^(User with "(?:[^"]*)" username) should be removed from (group with "(?:[^"]*)" name)$/
     */
    public function userShouldBeAddedToGroup(User $user, Group $group)
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_OK,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );


        $this->getEntityManager()->refresh($group);

        Assert::false($group->getUsers()->contains($user), 'User not removed from group');
    }

    /**
     * @Then /^I should not be allowed to remove user from the group$/
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
     * @Then /^I should receive and error$/
     */
    public function checkNotModified()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_NOT_FOUND,
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