<?php

declare(strict_types=1);

namespace InternationsBehat\Context;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext extends RawMinkContext implements KernelAwareContext
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
}