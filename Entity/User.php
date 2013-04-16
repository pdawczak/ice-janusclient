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

    /**
     * Only used when creating new accounts
     *
     * @var string
     */
    private $plainPassword;

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

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param \DateTime|null $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @param string $firstNames
     * @return User
     */
    public function setFirstNames($firstNames)
    {
        $this->firstNames = $firstNames;
        return $this;
    }

    /**
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @param string $lastNames
     * @return User
     */
    public function setLastNames($lastNames)
    {
        $this->lastNames = $lastNames;
        return $this;
    }

    /**
     * @param string $middleNames
     * @return User
     */
    public function setMiddleNames($middleNames)
    {
        $this->middleNames = $middleNames;
        return $this;
    }


    /**
     * @param string $title
     * @return User
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}