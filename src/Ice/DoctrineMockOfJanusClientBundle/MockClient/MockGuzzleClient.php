<?php

namespace Ice\DoctrineMockOfJanusClientBundle\MockClient;

use Doctrine\Common\Collections\ArrayCollection;
use Ice\DoctrineMockOfJanusClientBundle\Exception\CommandNotImplementedException;
use Ice\DoctrineMockOfJanusClientBundle\MockClient\AbstractGuzzleClient;
use Ice\DoctrineMockOfJanusClientBundle\MockCommand\MockGetUserCommand;
use Doctrine\ORM\EntityManager;
use Ice\JanusClientBundle\Entity\User;
use Ice\JanusClientBundle\Service\JanusUserProvider;

class MockGuzzleClient extends AbstractGuzzleClient implements JanusUserProvider
{
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

    public function getCommand($name, array $args = array())
    {
        switch ($name) {
            case 'GetUser':
                return new MockGetUserCommand(
                    $this->getUserRepository(),
                    $args
                );
                break;
            default:
                throw new CommandNotImplementedException('Command: '.$name.' is not supported');
        }
    }

    public function getUser($username)
    {
        $command = new MockGetUserCommand(
            $this->getUserRepository(),
            array('username' => $username)
        );
        return $command->execute();
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

    /**
     * @param array $filters
     *
     * @return User[]|ArrayCollection
     */
    public function getUsers(array $filters = array())
    {
        // TODO: Implement getUsers() method.
    }

    /**
     * @param string $term Search term
     *
     * @return User[]|ArrayCollection
     */
    public function searchUsers($term)
    {
        // TODO: Implement searchUsers() method.
    }
}
