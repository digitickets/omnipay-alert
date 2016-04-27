<?php

namespace Omnipay\Alert\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Alert Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
   /* public function __construct(RequestInterFace $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
        if (!isset($this->data->Status)) {
            throw new InvalidResponseException;
        }
    }*/

    protected $endpoint;
    public function __construct($purchaseRequest, $data, $endpoint)
    {
        parent::__construct($purchaseRequest, $data);
       $this->endpoint = $endpoint;
    }


    public function getMessage()
    {
        return (string) $this->data->Message;
    }

    public function getResult()
    {
        return (string) $this->data->Result;
    }

    public function getTransactionReference()
    {
        return $this->data->TransactionReferenceID;
    }

    public function isSuccessful()
    {
       // return 1 === (int) $this->data->Status;
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();//.'?'.http_build_query($this->getData());
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->getData();
        //return http_build_query($this->getData());
    }
}
