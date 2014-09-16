<?php

namespace Ice\JanusClientBundle\Response;

use JMS\Serializer\Annotation as JMS;

class FormError
{
    /**
     * @var FormError[]
     * @JMS\Type("array<string, Ice\JanusClientBundle\Response\FormError>")
     * @JMS\AccessType("public_method")
     */
    private $children = array();

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
    private $errors = array();

    /**
     * @param FormError[] $children
     * @return $this
     */
    public function setChildren($children)
    {
        if(null === $children){
            $this->children = array();
            return $this;
        }
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
     * Return the errors on this form as a map (keyed by name) of lists of strings. A key of "" indicates a top level
     * error.
     *
     * If $includeChildren is false, only errors which directly belong to this object will be returned. The map will
     * only ever contain one list in this case.
     *
     * @param bool $includeChildren
     * @return array
     */
    public function getErrorsAsAssociativeArray($includeChildren = true)
    {
        $errors = array();
        if($directErrors = $this->getErrors()){
            $errors[$this->getName()] = $directErrors;
        }
        if($includeChildren && is_array($this->getChildren())){
            foreach($this->getChildren() as $child){
                if(is_array($childErrors = $child->getErrorsAsAssociativeArray($includeChildren))){
                    $errors = array_merge($errors, $childErrors);
                }
            }
        }
        return $errors;
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