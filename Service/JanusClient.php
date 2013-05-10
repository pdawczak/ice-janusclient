<?php

namespace Ice\JanusClientBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Service\Client;
use Ice\JanusClientBundle\Entity\User;
use Ice\JanusClientBundle\Exception\AuthenticationException;
use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Serializer;

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
     * @param Client     $client
     * @param Serializer $serializer
     * @param string     $username
     * @param string     $password
     */
    public function __construct(Client $client, Serializer $serializer, $username, $password)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->client->setConfig(array(
            'curl.options' => array(
                'CURLOPT_USERPWD' => sprintf("%s:%s", $username, $password),
            ),
        ));
        $this->client->setDefaultHeaders(array(
            'Accept' => 'application/json',
        ));
    }

    /**
     * @param string $username
     *
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
     *
     * @return mixed
     * @throws \Exception|\Guzzle\Http\Exception\BadResponseException|\Guzzle\Service\Exception\ValidationException
     * @throws \Ice\JanusClientBundle\Exception\ValidationException
     */
    public function createUser(array $values)
    {
        try {
            $command = $this->client->getCommand('CreateUser', $values);
            $user = $command->execute();
            return $user;
        } catch (BadResponseException $badResponseException) {
            if (!$this->responseBodyToValidationException(
                $badResponseException->getResponse()->getBody(true),
                $badResponseException)
            ) {
                throw $badResponseException;
            }
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
            'username'      => $username,
            'attributeName' => $attributeName,
            'value'         => $attributeValue,
            'updatedBy'     => $updatedBy,
        ))->execute();
    }

    public function createAttribute($username, $attributeName, $attributeValue)
    {
        return $this->client->getCommand('CreateAttribute', array(
            'username'  => $username,
            'fieldName' => $attributeName,
            'value'     => $attributeValue,
        ))->execute();
    }

    /**
     * @param array $filters
     *
     * @return User[]|ArrayCollection
     */
    public function getUsers(array $filters = array())
    {

        return $this->client->getCommand('GetUsers', array(
            'query' => $filters,
        ))->execute();
    }

    /**
     * @param string $term Search term
     *
     * @return User[]|ArrayCollection
     */
    public function searchUsers($term)
    {
        return $this->client->getCommand('SearchUsers', array(
            'term' => $term,
        ))->execute();
    }

    /**
     * @param $username
     * @param $password
     *
     * @return User
     * @throws \Ice\JanusClientBundle\Exception\AuthenticationException when credentials are bad
     * @throws \Exception|\Guzzle\Http\Exception\BadResponseException when an unknown error (eg 500) occurs
     */
    public function authenticate($username, $password)
    {
        $this->client->addSubscriber(new CurlAuthPlugin($username, $password));
        try {
            return $this->client->getCommand('Authenticate')->execute();
        } catch (BadResponseException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                case 401:
                case 403:
                    throw new AuthenticationException();
                    break;
            }
            throw $e;
        }
    }

    /**
     * @param                 $responseBody
     * @param \Exception|null $previousException
     *
     * @return bool false if the exception could not be thrown
     * @throws \Ice\JanusClientBundle\Exception\ValidationException
     */
    private function responseBodyToValidationException($responseBody, $previousException = null)
    {
        try {
            /** @var $form \Ice\JanusClientBundle\Response\FormError */
            $form = $this->serializer->deserialize(
                $responseBody,
                'Ice\\JanusClientBundle\\Response\\FormError',
                'json'
            );
            if (!$form->getErrorsAsAssociativeArray()) {
                return false;
            }
        } catch (\Exception $deserializingException) {
            //We can't improve the exception - just re-throw the original
            return false;
        }
        throw new ValidationException($form, 'Validation error', 400, $previousException);
    }
}