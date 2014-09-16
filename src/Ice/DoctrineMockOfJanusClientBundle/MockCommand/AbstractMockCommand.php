<?php

namespace Ice\DoctrineMockOfJanusClientBundle\MockCommand;

use Guzzle\Common\Collection;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Service\ClientInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\OperationInterface;
use Guzzle\Service\Exception\CommandException;
use Ice\DoctrineMockOfVeritasClientBundle\Exception\MethodNotImplementedException;

abstract class AbstractMockCommand implements CommandInterface
{
    /**
     * Get the short form name of the command
     *
     * @return string
     */
    public function getName()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Get the API operation information about the command
     *
     * @return OperationInterface
     */
    public function getOperation()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Get the client object that will execute the command
     *
     * @return ClientInterface|null
     */
    public function getClient()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Set the client object that will execute the command
     *
     * @param ClientInterface $client The client object that will execute the command
     *
     * @return CommandInterface
     */
    public function setClient(ClientInterface $client)
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Get the request object associated with the command
     *
     * @return RequestInterface
     * @throws CommandException if the command has not been executed
     */
    public function getRequest()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Get the response object associated with the command
     *
     * @return Response
     * @throws CommandException if the command has not been executed
     */
    public function getResponse()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Get the result of the command
     *
     * @return Response By default, commands return a Response object unless overridden in a subclass
     * @throws CommandException if the command has not been executed
     */
    public function getResult()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Set the result of the command
     *
     * @param mixed $result Result to set
     *
     * @return self
     */
    public function setResult($result)
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Returns TRUE if the command has been prepared for executing
     *
     * @return bool
     */
    public function isPrepared()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Returns TRUE if the command has been executed
     *
     * @return bool
     */
    public function isExecuted()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Prepare the command for executing and create a request object.
     *
     * @return RequestInterface Returns the generated request
     * @throws CommandException if a client object has not been set previously or in the prepare()
     */
    public function prepare()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Get the object that manages the request headers that will be set on any outbound requests from the command
     *
     * @return Collection
     */
    public function getRequestHeaders()
    {
        throw new MethodNotImplementedException();
    }

    /**
     * Specify a callable to execute when the command completes
     *
     * @param mixed $callable Callable to execute when the command completes. The callable must accept a
     *                        {@see CommandInterface} object as the only argument.
     * @return CommandInterface
     * @throws InvalidArgumentException
     */
    public function setOnComplete($callable)
    {
        throw new MethodNotImplementedException();
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
        throw new MethodNotImplementedException();
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
        throw new MethodNotImplementedException();
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
        throw new MethodNotImplementedException();
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
        throw new MethodNotImplementedException();
    }

    /**
     * Get the array representation of an object
     *
     * @return array
     */
    public function toArray()
    {
        throw new MethodNotImplementedException();
    }
}
