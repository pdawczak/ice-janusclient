<?php

namespace Ice\JanusClientBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Ice\JanusClientBundle\Entity\User;

interface JanusUserProvider
{
    /**
     * @param string $username
     *
     * @return \Ice\JanusClientBundle\Entity\User
     */
    public function getUser($username);

    /**
     * @param array $filters
     *
     * @return User[]|ArrayCollection
     */
    public function getUsers(array $filters = array());

    /**
     * @param string $term Search term
     *
     * @return User[]|ArrayCollection
     */
    public function searchUsers($term);
} 
