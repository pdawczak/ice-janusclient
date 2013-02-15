<?php

namespace Ice\JanusClientBundle\Service;

use Ice\JanusClientBundle\Response\User;

use Doctrine\Common\Collections\ArrayCollection;

use Guzzle\Service\Client;

class JanusClient
{
    /**
     * @var \Guzzle\Service\Client
     */
    private $client;

    /**
     * @param \Guzzle\Service\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->setDefaultHeaders(array(
            'Accepts' => 'application/json',
        ));
    }

    /**
     * @param string $username
     * @return \Ice\JanusClientBundle\Response\User
     */
    public function getUser($username)
    {
        $user = $this->client->getCommand('GetUser', array(
            'username' => $username,
        ))->execute();

        return $user;
    }


    public function createUser(array $values)
    {
        return $this->client->getCommand('CreateUser', $values)->execute();
    }

    public function updateAttribute($username, $attributeName, $attributeValue, $updatedBy)
    {
        return $this->client->getCommand('UpdateAttribute', array(
            'username' => $username,
            'attributeName' => $attributeName,
            'value' => $attributeValue,
            'updatedBy' => $updatedBy,
        ))->execute();
    }

    public function createAttribute($username, $attributeName, $attributeValue)
    {
        return $this->client->getCommand('CreateAttribute', array(
            'username' => $username,
            'fieldName' => $attributeName,
            'value' => $attributeValue,
        ))->execute();
    }

    /**
     * @param array $filters
     * @return User[]|ArrayCollection
     */
    public function getUsers(array $filters = array())
    {

        return $this->client->getCommand('GetUsers', array(
            'query' => $filters,
        ))->execute();
    }
}