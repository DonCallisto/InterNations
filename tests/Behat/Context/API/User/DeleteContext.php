<?php

declare(strict_types=1);

namespace InternationsBehat\Context\API\User;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use InternationsBehat\Context\API\SharedContext;
use InternationsBehat\Context\BaseContext;
use InternationsBehat\Context\UserTransformer;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class DeleteContext extends BaseContext
{
    use UserTransformer;

    private $client;

    private $userRepo;

    private $responseStatus;

    public function __construct(Client $client, UserRepository $userRepository)
    {
        $this->client = $client;
        $this->userRepo = $userRepository;
    }

    /**
     * @When /^I try to delete (user with "(?:[^"]*)" username)$/
     */
    public function tryDeleteUser(User $userToDelete)
    {
        $userId = $userToDelete->getId();

        $token = SharedContext::get(SharedContext::TOKEN);
        $response = $this->client->delete(sprintf('users/%s', $userId), [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            'http_errors' => false,
        ]);

        $this->responseStatus = $response->getStatusCode();
    }

    /**
     * @Then /^(User with "(?:[^"]*)" username) should be deleted$/
     */
    public function userShouldBeDeleted(?User $user)
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_NO_CONTENT,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );

        Assert::null($user, 'User not deleted!');
    }

    /**
     * @Then /^I should not be allowed to delete user$/
     */
    public function userShouldNotBeAllowedToBeDeleted()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_FORBIDDEN,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );
    }

    protected function getUserRepo(): UserRepository
    {
        return $this->userRepo;
    }
}