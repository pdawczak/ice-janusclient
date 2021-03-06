<?php

namespace Ice\JanusClientBundle\Service;


use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\ClientInterface;
use JMS\Serializer\SerializerInterface;

use Ice\JanusClientBundle\Entity\User;
use Ice\JanusClientBundle\Exception\AuthenticationException;
use Ice\JanusClientBundle\Exception\ValidationException;

class JanusClient implements JanusUserProvider
{
    /**
     * @var ClientInterface
     */
    protected $client;
    
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

    public function __construct(ClientInterface $client, SerializerInterface $serializer = null)
    {
        $this->client     = $client;
        $this->serializer = $serializer;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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

        try {
            $command = $this->client->getCommand('UpdateUser', $array);
            $response = $command->execute();
            return $response;
        } catch (BadResponseException $badResponseException) {
            if (!$this->responseBodyToValidationException(
                $badResponseException->getResponse()->getBody(true),
                $badResponseException)
            ) {
                throw $badResponseException;
            }
        }

        return null;
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

    /**
     * Update multiple attributes at once. This performs a parallel guzzle request so is faster than calling
     * updateAttribute multiple times, but otherwise equivalent.
     *
     * $attributes is a simple array of fieldName - value pairs.
     *
     * @param string $username The username whose attributes are to be set
     * @param string $updatedBy The username of the setter
     * @param array $attributes An array of fieldName-value pairs. Value should be a plain (not serialized) string
     * @param bool $replaceNullWithEmptyString (optional) False to error on null instead of sending an empty string.
     */
    public function setAttributes($username, $updatedBy, array $attributes, $replaceNullWithEmptyString = true)
    {
        $commands = array();

        //Build an UpdateAttribute command for each attribute
        foreach ($attributes as $fieldName => $value) {

            //Guzzle treats null values as unset so will throw a client-side validation error if a value is set to null
            if (null === $value && $replaceNullWithEmptyString) {
                $value = '';
            }

            $commands[] = $this->client->getCommand('UpdateAttribute', array(
                'username' => $username,
                'attributeName' => $fieldName,
                'value' => $value,
                'updatedBy' => $updatedBy,
            ));
        }

        //Send commands in parallel
        $this->client->execute($commands);
    }

    /**
     * @param $username
     * @param $emailAddress
     * @return mixed
     */
    public function updateEmailAddress($username, $emailAddress)
    {
        try {
            $this->client->getCommand('UpdateEmailAddress', array(
                'username'      => $username,
                'email' => $emailAddress
            ))->execute();
        } catch (BadResponseException $badResponseException) {
            if (!$this->responseBodyToValidationException(
                $badResponseException->getResponse()->getBody(true),
                $badResponseException)
            ) {
                throw $badResponseException;
            }
        }
    }


    /**
     * @param $username
     * @param $title
     * @param $firstNames
     * @param $middleNames
     * @param $lastNames
     * @throws \Exception|\Guzzle\Http\Exception\BadResponseException
     * @return mixed
     */
    public function updateName($username, $title, $firstNames, $middleNames, $lastNames)
    {
        try {
            $this->client->getCommand('UpdateName', array(
                'username'      => $username,
                'title' => $title,
                'firstNames' => $firstNames,
                'middleNames' => $middleNames,
                'lastNames' => $lastNames
            ))->execute();
        } catch (BadResponseException $badResponseException) {
            if (!$this->responseBodyToValidationException(
                $badResponseException->getResponse()->getBody(true),
                $badResponseException)
            ) {
                throw $badResponseException;
            }
        }
    }

    /**
     * @param $username
     * @param \DateTime $dob
     * @throws \Exception|\Guzzle\Http\Exception\BadResponseException
     */
    public function updateDob($username, \DateTime $dob = null)
    {
        try {
            $this->client->getCommand('UpdateDateOfBirth', array(
                'username'      => $username,
                'dob' => $dob ? $dob->format('Y-m-d') : ""
            ))->execute();
        } catch (BadResponseException $badResponseException) {
            if (!$this->responseBodyToValidationException(
                $badResponseException->getResponse()->getBody(true),
                $badResponseException)
            ) {
                throw $badResponseException;
            }
        }
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
        try {
            $command = $this->client->getCommand('Authenticate');
            $command->prepare();
            $command->getRequest()->setAuth($username, $password, CURLAUTH_BASIC);
            return $command->execute();
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
