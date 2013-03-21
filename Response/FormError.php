<?php

namespace Ice\JanusClientBundle\Response;

use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

class FormError{
    /**
     * @var FormError[]
     * @JMS\Type("array<string, Ice\JanusClientBundle\Response\Form>")
     * @JMS\AccessType("public_method")
     */
    private $children;

    /**
     * @var string
     * @JMS\Exclude;
     */
    private $name;

    /**
     * @var FormError
     * @JMS\Exclude;
     */
    private $parent;

    /**
     * @var string[]
     * @JMS\Type("array<string>")
     */
    private $errors;

    public function __construct()
    {
        $this->children = [];
        $this->errors = [];
    }

    /**
     * @param FormError[] $children
     * @return $this
     */
    public function setChildren($children)
    {
        foreach($children as $name=>$child){
            $child->setName($name)->setParent($this);
        }
        $this->children = $children;
        return $this;
    }

    /**
     * @return FormError[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $name
     * @return FormError
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Ice\JanusClientBundle\Response\FormError $parent
     * @return FormError
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return \Ice\JanusClientBundle\Response\FormError
     */
    public function getParent()
    {
        return $this->parent;
    }
}