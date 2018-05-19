<?php

declare(strict_types=1);

namespace InternationsBehat\Context\API\Group;

use AppBundle\Entity\Group;
use AppBundle\Repository\GroupRepository;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use InternationsBehat\Context\API\SharedContext;
use InternationsBehat\Context\BaseContext;
use InternationsBehat\Context\GroupTransformer;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class CreateContext extends BaseContext
{
    use GroupTransformer;

    const GROUP_NAME = 'aGroup';

    private $client;

    private $groupRepo;

    private $responseStatus;

    private $responseBody;

    public function __construct(Client $client, GroupRepository $groupRepository)
    {
        $this->client = $client;
        $this->groupRepo = $groupRepository;
    }

    /**
     * @When /^I try to create a group$/
     */
    public function tryGroupCreation()
    {
        $token = SharedContext::get(SharedContext::TOKEN);
        $response = $this->client->post('groups', [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            RequestOptions::JSON => [
                'name' => self::GROUP_NAME
            ],
            'http_errors' => false,
        ]);

        $this->responseStatus = $response->getStatusCode();
        $this->responseBody = $response->getBody()->getContents();
    }

    /**
     * @Then /^Group should be created$/
     */
    public function groupShouldBeCreated()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_CREATED,
            sprintf('There was an error during group creation: %s', $this->responseBody)
        );

        $arrayRes = json_decode($this->responseBody, true);
        Assert::keyExists($arrayRes, 'id');
        Assert::keyExists($arrayRes, 'name');
        Assert::keyExists($arrayRes, 'users');

        $user = $this->fromNameToGroup(self::GROUP_NAME);
        Assert::isInstanceOf($user, Group::class, 'Group not created!');
    }

    /**
     * @Then /^I should not be allowed to create a group/
     */
    public function groupShouldNotBeAllowedToBeCreated()
    {
        Assert::eq($this->responseStatus, Response::HTTP_FORBIDDEN, 'Group has been created');
    }

    protected function getGroupRepo(): GroupRepository
    {
        return $this->groupRepo;
    }
}