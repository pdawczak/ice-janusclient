<?php

namespace Ice\DoctrineMockOfJanusClientBundle\MockClient;

use Doctrine\Common\Collections\ArrayCollection;
use Ice\DoctrineMockOfJanusClientBundle\Exception\CommandNotImplementedException;
use Ice\DoctrineMockOfJanusClientBundle\MockClient\AbstractGuzzleClient;
use Ice\DoctrineMockOfJanusClientBundle\MockCommand\MockGetUserCommand;
use Doctrine\ORM\EntityManager;
use Ice\JanusClientBundle\Entity\User;
use Ice\JanusClientBundle\Service\JanusUserProvider;

class MockGuzzleClient extends AbstractGuzzleClient
{
    const MOCK_CLASS_NAME_TPL = 'Ice\DoctrineMockOfJanusClientBundle\MockCommand\Mock%sCommand';

    /**
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setDefaultHeaders($headers)
    {
        //Ignore
    }

    public function getCommand($commandName, array $args = array())
    {
        $mockClassName = sprintf(self::MOCK_CLASS_NAME_TPL, ucfirst($commandName));

        if (! class_exists($mockClassName)) {
            throw new CommandNotImplementedException(sprintf('Command: "%s" is not supported', $commandName));
        }

        return new $mockClassName(
            $this->getUserRepository(),
            $args
        );
    }

    public function setSerializer($anyArg)
    {
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserRepository()
    {
        return $this->entityManager->getRepository('IceDoctrineMockOfJanusClientBundle:User');
    }
}
