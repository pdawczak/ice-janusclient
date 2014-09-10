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

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Get the array representation of an object
     *
     * @return array
     */
    public function toArray()
    {
    }
}
