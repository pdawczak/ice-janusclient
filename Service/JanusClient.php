<?php

namespace Ice\JanusClientBundle\Service;

use Guzzle\Http\Exception\BadResponseException;
use Ice\JanusClientBundle\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

use Guzzle\Service\Client;

use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Valid;

class JanusClient
{
    /**
     * @var \Guzzle\Service\Client
     */
    private $client;

    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    /**
     * @param \Guzzle\Service\Client $client
     */
    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->client->setDefaultHeaders(array(
            'Accepts' => 'application/json',
        ));
    }

    /**
     * @param string $username
     * @return \Ice\JanusClientBundle\Entity\User
     */
    public function getUser($username)
    {
        $user = $this->client->getCommand('GetUser', array(
            'username' => $username,
        ))->execute();

        return $user;
    }


    /**
     * @param array $values
     * @return mixed
     * @throws \Exception|\Guzzle\Http\Exception\BadResponseException
     * @throws \Ice\JanusClientBundle\Exception\ValidationException
     */
    public function createUser(array $values)
    {
        try{
            $user = $this->client->getCommand('CreateUser', $values)->execute();
            return $user;
        }
        catch(BadResponseException $badResponseException){
            try{
                $form = $this->serializer->deserialize(
                    $badResponseException->getResponse()->getBody(true),
                    'Ice\\JanusClientBundle\\Response\\FormError',
                    'json'
                );
            }
            catch(\Exception $deserializingException){
                //We can't improve the exception - just re-throw the original
                throw $badResponseException;
            }
            throw new ValidationException($form, 'Validation error', 400, $badResponseException);
        }
    }

    public function updateUser($username, array $values)
    {
        $array = array(
            'username' => $username,
        );

        $array = array_merge($values, $array);

        return $this->client->getCommand('UpdateUser', $array)->execute();
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