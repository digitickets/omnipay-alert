<?php

namespace Omnipay\Alert\Message;


use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return ($this->data['Status'] == true && $this->data['Result'] == 'Captured') ? $this->data['Status'] : null;
    }
    public function getTransactionReference()
    {
        return isset($this->data['TransactionReferenceID']) ? $this->data['TransactionReferenceID'] : null;
    }

    public function getCode()
    {
        return isset($this->data['Code']) ? $this->data['Code'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['Message']) ? $this->data['Message'] : null;
    }

    public function getMerchantReference()
    {
        return isset($this->data['MerchantReference']) ? $this->data['MerchantReference'] : null;
    }

    public function getResult()
    {
        return isset($this->data['Result']) ? $this->data['Result'] : null;
    }

}
