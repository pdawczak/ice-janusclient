<?php

namespace Ice\JanusClientBundle\Response;

use JMS\Serializer\Annotation\SerializedName,
    JMS\Serializer\Annotation\Type;

class UserAttribute
{
    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("fieldName")
     */
    private $fieldName;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @Type("DateTime")
     */
    private $created;

    /**
     * @var \DateTime|null
     *
     * @Type("DateTime")
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