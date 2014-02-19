<?php

namespace Ice\JanusClientBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Service\Client;
use Ice\JanusClientBundle\Entity\User;
use Ice\JanusClientBundle\Exception\AuthenticationException;
use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\SerializerInterface;

class JanusClient extends Client
{
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

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
        $user = $this->getCommand('GetUser', array(
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
            $command = $this->getCommand('CreateUser', $values);
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
            $command = $this->getCommand('UpdateUser', $array);
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
        return $this->getCommand('UpdateAttribute', array(
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

            $commands[] = $this->getCommand('UpdateAttribute', array(
                'username' => $username,
                'attributeName' => $fieldName,
                'value' => $value,
                'updatedBy' => $updatedBy,
            ));
        }

        //Send commands in parallel
        $this->execute($commands);
    }

    /**
     * @param $username
     * @param $emailAddress
     * @return mixed
     */
    public function updateEmailAddress($username, $emailAddress)
    {
        try {
            $this->getCommand('UpdateEmailAddress', array(
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

    public function createAttribute($username, $attributeName, $attributeValue)
    {
        return $this->getCommand('CreateAttribute', array(
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

        return $this->getCommand('GetUsers', array(
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
        return $this->getCommand('SearchUsers', array(
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
            $command = $this->getCommand('Authenticate');
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