<?php

namespace Ice\JanusClientBundle\Exception;

use Guzzle\Http\Exception\ClientErrorResponseException;

use Ice\JanusClientBundle\Response\FormError;

class ValidationException extends ClientErrorResponseException
{
    /** @var FormError */
    private $form;

    public function __construct(FormError $form, $message = '', $code = 0, \Exception $previous = null){
        $this->form = $form;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \Ice\JanusClientBundle\Response\FormError
     */
    public function getForm()
    {
        return $this->form;
    }
}