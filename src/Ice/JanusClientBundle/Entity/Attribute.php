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
    protected $fieldName;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected $value;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    protected $created;

    /**
     * @var \DateTime|null
     *
     * @JMS\Type("DateTime")
     */
    protected $updated;

    /**
     * @return \DateTime
     */
    protected function getCreated()
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
