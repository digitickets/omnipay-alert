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
            'BankMerchantNo'    => '',
            'userID'            => '',
            'password'          => '',
            'liveEndPoint'      => ''
        );
    }

    public function getEndPoint()
    {
    	return $this->getParameter('liveEndPoint');
    }

    public function setEndPoint($value)
    {
    	return $this->setParameter('liveEndPoint', $value);
    }

    public function getBankMerchantNo()
    {
        return $this->getParameter('BankMerchantNo');
    }

    public function setBankMerchantNo($value)
    {
        return $this->setParameter('BankMerchantNo', $value);
    }

    public function getUserID()
    {
        return $this->getParameter('userID');
    }

    public function setUserID($value)
    {
        return $this->setParameter('userID', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getMerchantReference()
    {
        return $this->getParameter('MerchantReference');
    }

    public function setMerchantReference($value)
    {
        return $this->setParameter('MerchantReference', $value);
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
