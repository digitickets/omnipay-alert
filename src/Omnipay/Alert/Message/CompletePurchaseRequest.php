<?php

namespace Omnipay\Alert\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Alert Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
       return $this->httpRequest->request->all();
    }

    public function getMessage()
    {
        return $this->getParameter('Message');
    }
    public function setMessage($value)
    {
        return $this->setParameter('Message', $value);
    }

    public function getCode()
    {
        return $this->getParameter('Code');
    }

    public function setCode($value)
    {
        return $this->setParameter('Code', $value);
    }
    public function getResult()
    {
        return $this->getParameter('Result');
    }

    public function setResult($value)
    {
        return $this->setParameter('Result', $value);
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
