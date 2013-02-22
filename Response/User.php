<?php

namespace Ice\JanusClientBundle\Response;

use JMS\Serializer\Annotation\SerializedName,
    JMS\Serializer\Annotation\Type;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    /**
     * @var integer
     *
     * @Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $username;

    /**
     * @var boolean
     *
     * @Type("boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @Type("DateTime")
     */
    private $lastLogin;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $email;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $title;

    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("firstNames")
     */
    private $firstNames;

    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("middleNames")
     */
    private $middleNames;

    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("lastNames")
     */
    private $lastNames;

    /**
     * @Type("ArrayCollection<Ice\JanusClientBundle\Response\Attribute>")
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
}