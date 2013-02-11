<?php

namespace Ice\JanusClientBundle\Service;

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

}