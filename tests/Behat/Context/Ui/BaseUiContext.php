<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseUiContext extends RawMinkContext implements KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var ORMPurger
     */
    private $purger;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function setPurger()
    {
        $this->purger = new ORMPurger($this->getEntityManager());
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @AfterScenario
     */
    public function purgeDb()
    {
        $this->purger->purge();
    }

    protected function findUser(string $username): ?User
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::class);

        return $repo->findOneBy(['username' => $username]);
    }

    protected function findGroup(string $username): ?Group
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Group::class);

        return $repo->findOneBy(['name' => $username]);
    }
}