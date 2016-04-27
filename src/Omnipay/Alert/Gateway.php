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
            'password'          => ''
        );
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

    public function purchase(array $parameters = array())
    {
        return $this->CreateRequest('\Omnipay\Alert\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->CreateRequest('\Omnipay\Alert\Message\CompletePurchaseRequest', $parameters);
    }

}
