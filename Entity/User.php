<?php

namespace Ice\JanusClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    /**
     * @var integer
     *
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $username;

    /**
     * @var boolean
     *
     * @JMS\Type("boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    private $lastLogin;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $email;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("firstNames")
     */
    private $firstNames;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("middleNames")
     */
    private $middleNames;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("lastNames")
     */
    private $lastNames;

    /**
     * @var \DateTime|null
     *
     * @JMS\Type("DateTime")
     */
    private $dob;

    /**
     * @JMS\Type("ArrayCollection<Ice\JanusClientBundle\Entity\Attribute>")
     */
    private $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstNames()
    {
        return $this->firstNames;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLastNames()
    {
        return $this->lastNames;
    }

    /**
     * @return string
     */
    public function getMiddleNames()
    {
        return $this->middleNames;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getFullName()
    {
        $nameParts = array(
            $this->getTitle(),
            $this->getFirstNames(),
            $this->getMiddleNames(),
            $this->getLastNames(),
        );

        $nameParts = array_filter($nameParts);

        return implode(" ", $nameParts);
    }

    /**
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttributeByName($name)
    {
        return $this->getAttributes()->filter(function(Attribute $attribute) use ($name) {
            return $attribute->getFieldName() === $name;
        })->first();
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @return \DateTime|null
     */
    public function getDob()
    {
        return $this->dob;
    }
}