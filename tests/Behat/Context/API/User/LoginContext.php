<?php

declare(strict_types=1);

namespace InternationsBehat\Context\API\User;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use GuzzleHttp\Client;
use InternationsBehat\Context\API\SharedContext;
use InternationsBehat\Context\BaseContext;
use InternationsBehat\Context\UserTransformer;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class LoginContext extends BaseContext
{
    use UserTransformer;

    private $userRepo;

    private $jwtManager;

    private $client;

    public function __construct(
        UserRepository $userRepository,
        JWTManager $JWTManager,
        Client $client
    ) {
        $this->userRepo = $userRepository;
        $this->jwtManager = $JWTManager;
        $this->client = $client;
    }

    /**
     * @Given /^I log in as (user with "(?:[^"]*)" username)$/
     */
    public function login(User $user)
    {
        $token = $this->jwtManager->create($user);
        SharedContext::set(SharedContext::TOKEN, $token);
    }

    protected function getUserRepo(): UserRepository
    {
        return $this->userRepo;
    }
}