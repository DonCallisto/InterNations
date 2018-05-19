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

class RegistrationContext extends BaseContext
{
    use UserTransformer;

    const NEW_USER_USERNAME = 'username';

    private $client;

    private $userRepo;

    private $responseStatus;

    private $responseBody;

    public function __construct(Client $client, UserRepository $userRepository)
    {
        $this->client = $client;
        $this->userRepo = $userRepository;
    }

    /**
     * @Given /^I try to create a user$/
     */
    public function tryUserRegistration()
    {
        $token = SharedContext::get(SharedContext::TOKEN);
        $response = $this->client->post('users', [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            RequestOptions::JSON => [
                'username' => self::NEW_USER_USERNAME,
                'email' => 'email@email.foo',
                'password' => 'password',
                'name' => 'Samuele'
            ],
            'http_errors' => false,
        ]);

        $this->responseStatus = $response->getStatusCode();
        $this->responseBody = $response->getBody()->getContents();
    }

    /**
     * @Then /^User should be created$/
     */
    public function userShouldBeCreated()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_CREATED,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );

        $arrayRes = json_decode($this->responseBody, true);
        Assert::keyExists($arrayRes, 'id');
        Assert::keyExists($arrayRes, 'username');
        Assert::keyExists($arrayRes, 'email');
        Assert::keyExists($arrayRes, 'name');

        $user = $this->fromUsernameToUser(self::NEW_USER_USERNAME);
        Assert::isInstanceOf($user, User::class, 'User not created!');
    }

    /**
     * @Then /^I should not be allowed to create user$/
     */
    public function userShouldNotBeAllowedToBeCreated()
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