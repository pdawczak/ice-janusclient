<?php

namespace Ice\DoctrineMockOfJanusClientBundle\MockCommand;

use Guzzle\Service\Exception\CommandException;
use Ice\JanusClientBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class MockGetUserCommand extends AbstractMockCommand
{
    /**
     * @var EntityRepository;
     */
    private $userRepository;

    /**
     * @var array
     */
    private $args;

    public function __construct(
        $userRepository,
        $args
    )
    {
        $this->args = $args;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the command and return the result
     *
     * @return mixed Returns the result of {@see CommandInterface::execute}
     * @throws CommandException if a client has not been associated with the command
     */
    public function execute()
    {
        return $this->userRepository->findOneBy(['username' => $this->args['username']]);
    }
}
