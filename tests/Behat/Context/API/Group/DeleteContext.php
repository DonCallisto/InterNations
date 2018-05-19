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

class DeleteContext extends BaseContext
{
    use GroupTransformer;

    private $client;

    private $groupRepo;

    private $responseStatus;

    public function __construct(Client $client, GroupRepository $groupRepository)
    {
        $this->client = $client;
        $this->groupRepo = $groupRepository;
    }

    /**
     * @When /^I try to delete (group with "(?:[^"]*)" name)$/
     */
    public function tryDeleteGroup(Group $group)
    {
        $groupId = $group->getId();

        $token = SharedContext::get(SharedContext::TOKEN);
        $response = $this->client->delete(sprintf('groups/%s', $groupId), [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            'http_errors' => false,
        ]);

        $this->responseStatus = $response->getStatusCode();
    }

    /**
     * @Then /^(Group with "(?:[^"]*)" name) should be deleted$/
     */
    public function groupShouldBeDeleted(?Group $group)
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_NO_CONTENT,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );

        Assert::null($group, 'Group not deleted!');
    }

    /**
     * @Then /^(Group with "(?:[^"]*)" name) should not be deleted$/
     */
    public function groupShouldNotBeDeleted(?Group $group)
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_FORBIDDEN,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );

        Assert::isInstanceOf($group, Group::class, 'Group deleted!');
    }

    /**
     * @Then /^I should not be allowed to delete group/
     */
    public function groupShouldNotBeAllowedToBeDeleted()
    {
        Assert::eq(
            $this->responseStatus,
            Response::HTTP_FORBIDDEN,
            sprintf('Response status not expected: %s', $this->responseStatus)
        );
    }

    protected function getGroupRepo(): GroupRepository
    {
        return $this->groupRepo;
    }
}