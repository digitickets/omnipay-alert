<?php

namespace Omnipay\Alert\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * DataCash Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
       // return $this->httpRequest->request->all();
        $requestData = $this->getRequestData();

        return $requestData;
    }

    public function getRequestData()
    {
        $data = ($this->getCallbackMethod() == 'POST') ?
            $this->httpRequest->request->all() :
            $this->httpRequest->query->all();
        if (empty($data)) {
            throw new InvalidResponseException(sprintf(
                "No callback data was passed in the %s request",
                $this->getCallbackMethod()
            ));
        }
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
