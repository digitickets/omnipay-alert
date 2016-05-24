<?php

namespace Omnipay\Alert\Message;

use Omnipay\Alert\Message\Helper\clsRequest;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Alert Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public $Address;
    public $Postcode;
    public $UserDefinedField1;
    public $UserDefinedField2;
    public $UserDefinedField3;
    public $UserDefinedField4;
    public $UserDefinedField5;
    public $ClientIP;
    public $AMEXPurchaseType;

    const WSDL = ""; // Physical path to WSDL file. Should be in a non web-accessible directory
    const SecureAPGPath = ''; // Physical path to secure.apg file. Should be in a non web-accessible directory
    const APGNamespace = "https://pgws.alert.com.mt/"; // APGNamespace. Do not modify.
    const CurrencyCode = "";
    const CurrencyNumber = "";
    const BankMerchantNo = "";
    const Authorization = "Authorization";
    const VoidAuthorization = "VoidAuthorization";
    const Capture = "Capture";
    const VoidCapture = "VoidCapture";
    const Purchase = "Purchase";
    const VoidPurchase = "VoidPurchase";
    const Credit = "Credit";
    const VoidCredit = "VoidCredit";
    const Tentative = "Tentative";
    const Unknown = "Unknown";
    const Electronic = "Electronic";
    const Physical = "Physical";

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

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
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


    public function getData()
    {

        if (!$this->getNotifyUrl()) {
        $this->validate('returnUrl');
        }
        $data =  new clsRequest();

        $data->KeyID = '';
        $data->TransactionReferenceID = '';
        $data->MerchantReference = $this->getTransactionId();
        $data->BankMerchantNo = $this->getMerchantNumber();
        $data->BankAlias = '';
        $data->TransactionType = '';
        $data->CardBrand = '';
        $data->CurrencyNumber = $this->getCurrency();
        $data->CurrencyCode = 'EUR';
        $data->Amount = $this->getAmount();
        $data->CardNumber ='';
        $data->CVV2 = '';
        $data->ExpiryYear = '';
        $data->ExpiryMonth = '';
        $data->ExpiryDay = '';
        $data->CardHolder = '';
        $data->Address = $this->getCard()->getAddress1();
        $data->Postcode = $this->getCard()->getPostcode();
        $data->Name = $this->getCard()->getName();;
        $data->Country = $this->getCard()->getCountry();
        $data->Phone = $this->getCard()->getPhone();
        $data->Email = $this->getCard()->getEmail();
        $data->ClientIP = $_SERVER['REMOTE_ADDR'];
        $data->AMEXPurchaseType = '';
        $data->notifyUrl = $this->getNotifyUrl();
        $data->returnUrl = $this->getReturnUrl();
        $data->UserDefinedField1 = substr(preg_replace('/[\s\(\)]+/','',$this->getDescription()), 0, 20);
        $data->CheckoutRequest = '';

        return $data;
    }

    /**
     * @param SimpleXMLElement $data
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     */
    public function sendData($data)
    {
      return $this->response = new Response($this, $data);
    }

}
