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

    public function __construct($purchaseRequest, $data)
    {
        parent::__construct($purchaseRequest, $data);
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
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }

}
