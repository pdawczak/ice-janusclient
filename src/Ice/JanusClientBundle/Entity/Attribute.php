<?php

namespace Ice\JanusClientBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Attribute
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("fieldName")
     */
    private $fieldName;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    private $created;

    /**
     * @var \DateTime|null
     *
     * @JMS\Type("DateTime")
     */
    private $updated;

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}