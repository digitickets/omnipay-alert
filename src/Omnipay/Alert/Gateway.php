<?php

namespace Omnipay\Alert;

use Omnipay\Alert\Message\CompletePurchaseRequest;
use Omnipay\Alert\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * Alert Gateway
 *
 *
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Alert';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantNumber'    => '',
            'endpoint'          => ''
        );
    }

    public function getMerchantNumber()
    {
        return $this->getParameter('merchantNumber');
    }

    public function setMerchantNumber($value)
    {
        return $this->setParameter('merchantNumber', $value);
    }

    public function getEndpoint()
    {
        return $this->getParameter('endpoint');
    }

    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function getCallbackMethod()
    {
        return $this->getParameter('callbackMethod');
    }
    public function setCallbackMethod($value)
    {
        return $this->setParameter('callbackMethod', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }
    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->CreateRequest('\Omnipay\Alert\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->CreateRequest('\Omnipay\Alert\Message\CompletePurchaseRequest', $parameters);
    }

}
