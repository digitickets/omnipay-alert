<?php

namespace Omnipay\Alert\Message;

use DOMDocument;
use Omnipay\Alert\Message\Helper;
use Omnipay\Alert\Message\Helper\clsRequest;
use Omnipay\Omnipay;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;
use SoapClient;
use SoapHeader;

/**
 * Alert Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public $KeyID;
    public $TransactionReferenceID;
    public $MerchantReference;
    public $BankMerchantNo;
    public $BankAlias;
    public $TransactionType;
    public $CardBrand;
    public $CurrencyNumber;
    public $CurrencyCode;
    public $Amount;
    public $CardNumber;
    public $CVV2;
    public $ExpiryYear;
    public $ExpiryMonth;
    public $ExpiryDay;
    public $CardHolder;
    public $Address;
    public $Postcode;
    public $UserDefinedField1;
    public $UserDefinedField2;
    public $UserDefinedField3;
    public $UserDefinedField4;
    public $UserDefinedField5;
    public $ClientIP;
    public $AMEXPurchaseType;
    protected $liveEndpoint = 'http://test.local/agp/checkout.php';


    const WSDL = "service.wsdl"; // Physical path to WSDL file. Should be in a non web-accessible directory
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

    public function getCardBrand()
    {
        try {
            foreach ($this->getAllowedCardBrands($this->getBankMerchantNo()) as $cardBrands) {
                if (strtoupper($this->getCard()->getBrand()) == preg_replace('/\s+/', '', $cardBrands->DESCRIPTION)) {
                    return $cardBrands->CODE;
                }
            }
        } catch (\ Exception $ex) {
            $ex->getMessage();
            throw new \Exception("Unable to retrieve card brand.");
        }
    }

    public function getCurrency()
    {
        return $this->GetSupportedCurrency($this->getBankMerchantNo());
    }

    public function getTransactionID()
    {
        $data = new \SimpleXMLElement('<RequestValue/>');
        $data->addChild('TransactionType', 'Tentative');
        $data->addChild('BankMerchantNo', $this->getBankMerchantNo());
        $data->addChild('MerchantReference', $this->getMerchantReference());
        $data->addChild('CurrencyNumber', $this->getCurrency());
        $data->addChild('Amount', $this->getAmount());
        $data->addChild('ClientIP', $_SERVER['REMOTE_ADDR']);

        $result = $this->ProcessTransaction($data, false);

        return $result->TransactionReferenceID;
    }

    public function getData()
    {
        //$this->getCard()->validate();
        //$this->validate('amount');
        if (!$this->getNotifyUrl()) {
        $this->validate('returnUrl');
        }
        $data =  new clsRequest();

        $data->KeyID = '';
        $data->TransactionReferenceID = $this->getTransactionID();
        $data->MerchantReference = $this->getMerchantReference();
        $data->BankMerchantNo = $this->getBankMerchantNo();
        $data->BankAlias = '';
        $data->TransactionType = '';
        $data->CardBrand = '';
        $data->CurrencyNumber = $this->getCurrency()->Number;
        $data->CurrencyCode = $this->getCurrency()->Code;
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
        $data->CheckoutRequest = '';

        return $data;
    }

    public function ProcessTransaction($objRequest, $encryptCardDetails = false) {

        try {
            $resultNode = $this->GetInitToken();
            $objRequest->KeyID = (string) $resultNode->KeyID;

            if($encryptCardDetails){
                $objRequest->CardNumber = $this->EncryptString($objRequest->CardNumber, $resultNode->Key);
                $objRequest->CVV2 = $this->EncryptString($objRequest->CVV2, $resultNode->Key);
                $objRequest->ExpiryYear = $this->EncryptString($objRequest->ExpiryYear, $resultNode->Key);
                $objRequest->ExpiryMonth = $this->EncryptString($objRequest->ExpiryMonth, $resultNode->Key);
                $objRequest->ExpiryDay = $this->EncryptString($objRequest->ExpiryDay, $resultNode->Key);
                $objRequest->CardHolder = $this->EncryptString($objRequest->CardHolder, $resultNode->Key);
            } else {
                $objRequest->CardNumber;
                $objRequest->CVV2;
                $objRequest->ExpiryYear;
                $objRequest->ExpiryMonth;
                $objRequest->CardHolder;
            }

            $soap = @new SoapClient(PurchaseRequest::WSDL, array('trace' => 1));

            /* set the Headers of Soap Client. */
            $soap->__setSoapHeaders($this->GetSoapHeader());

            // debugObject($objRequest, "REQUEST OBJECT SENT FOR TRANSACTION PROCESSING");

            $params = array('RequestValue' => $objRequest);

            $soap->ProcessTransaction($params);

            $xml = @simplexml_load_string($soap->__getLastResponse());
            $parentNode = $xml->xpath("soap:Body");
            $resultNode = $parentNode[0]->ProcessTransactionResponse[0]->ProcessTransactionResult[0];

            return $resultNode;
        } catch (\Exception $ex) {
            $this->handleException($ex);
            throw new \Exception("Unable to process transaction.");
        }
    }

    public function GetSupportedCurrency($BankMerchantID) {

        try {

            $soap = @new SoapClient(PurchaseRequest::WSDL, array('trace' => 1));

            /* set the Headers of Soap Client. */
            $soap->__setSoapHeaders($this->GetSoapHeader());

            $params = array('BankMerchantID'=>$BankMerchantID);
            $soap->GetSupportedCurrency($params);

            $xml = @simplexml_load_string($soap->__getLastResponse());
            $parentNode = $xml->xpath("soap:Body");
            $resultNode = $parentNode[0]->GetSupportedCurrencyResponse[0]->GetSupportedCurrencyResult[0];
            return $resultNode;
        } catch (\Exception $ex) {
            $this->handleException($ex);
            throw new \Exception("Unable to retrieve currency.");
        }
    }

    public function GetAllowedCardBrands($BankMerchantID) {

        try {
            $soap = @new SoapClient(PurchaseRequest::WSDL, array('trace' => 1));

            /* set the Headers of Soap Client. */
            $soap->__setSoapHeaders($this->GetSoapHeader());

            $params = array('BankMerchantID'=>$BankMerchantID);
            $soap->GetAllowedCardBrands($params);

            $xml = @simplexml_load_string($soap->__getLastResponse())->xpath("//Card");

            return $xml;
        } catch (\Exception $ex) {
            $ex->getMessage();
            throw new \Exception("Unable to retrieve cards.");
        }
    }


    private function GetSoapHeader() {

        /* Body of the SOAP Header. */
        $headerbody = array('Identification' => array('UserID' => $this->getUserID(),
                                                      'Password' => $this->getPassword(),
                                                      'ReferringURL' => $this->selfURL()));

        /* Create SOAP Header. */
        return new SOAPHeader("https://pgws.alert.com.mt/", 'SecureSoapHeader', $headerbody);
    }
    /* <summary>
      * Returns the current URL as string
      * </summary>
      */

    private function selfURL() {
        if (!isset($_SERVER['REQUEST_URI'])) {
            $serverrequri = $_SERVER['PHP_SELF'];
        } else {
            $serverrequri = $_SERVER['REQUEST_URI'];
        }
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
        return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
    }

    private function strleft($s1, $s2) {
        return substr($s1, 0, strpos($s1, $s2));
    }
    private function GetInitToken() {

        try {
            $soap = new SoapClient(PurchaseRequest::WSDL, array('trace' => 1));

            /* set the Headers of Soap Client. */
            $header = $this->GetSoapHeader();

            $soap->__setSoapHeaders($this->GetSoapHeader());

            $soap->InitToken();

            $xml = @simplexml_load_string($soap->__getLastResponse());
            $parentNode = $xml->xpath("soap:Body");
            $resultNode = $parentNode[0]->InitTokenResponse[0]->InitTokenResult[0];

            return $resultNode;
        } catch (\Exception $ex) {
            $this->handleException($ex);
            throw new \Exception("Unable to retrieve InitToken.");
        }
    }
    public function handleException($ex) {
        if ( defined( 'DEBUG' ) && DEBUG == true ){
            echo 'Caught exception: ',  $ex->getMessage(), "<br><br>";
        }
        // TODO: log exception and show error page
    }

    private function EncryptString($bytText, $xmlkey) {
        require_once '../phpseclib/Crypt/RSA.php';
        $rsa = new phpCrypt_RSA();
        $xml = new DOMDocument();
        $xml->loadXML($xmlkey);

        $decodedModulus = base64_decode($xml->getElementsByTagName('Modulus')->item(0)->nodeValue);

        $modulus = new Math_BigInteger($decodedModulus, 256);
        $exponent = new Math_BigInteger(base64_decode($xml->getElementsByTagName('Exponent')->item(0)->nodeValue), 256);

        $rsa->loadKey(array("modulus" => $modulus, "exponent" => $exponent), CRYPT_RSA_PUBLIC_FORMAT_RAW);

        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_OAEP);

        /* Dim intKeySize As Integer = PublicKey.KeySize \ 8 */
        $intKeySize = strlen($decodedModulus);

        /* Dim intMaxLength As Integer = intKeySize - 42 */
        $intMaxLength = floor(($intKeySize - 42) / 4);

        /* Dim intDataLength As Integer = bytText.Length */
        $intDataLength = strlen($bytText);

        /* Dim intIterations As Integer = intDataLength \ intMaxLength */
        $intIterations = floor($intDataLength / $intMaxLength);

        /* Dim sbText As New System.Text.StringBuilder() */
        $sbText = "";

        /* For i As Integer = 0 To intIterations */
        for ($i = 0; $i <= $intIterations; $i++) {
            /* Buffer.BlockCopy(bytText, intMaxLength * i, bytTempBytes, 0, bytTempBytes.Length) */
            $bytTempBytes = substr($bytText, $intMaxLength * $i, $intMaxLength);
            $bytTempBytes = mb_convert_encoding($bytTempBytes, "UTF-32LE");

            /* Dim bytEncryptedBytes As Byte() = objRSACryptoServiceProvider.Encrypt(bytTempBytes, True) */
            $bytEncryptedBytes = $rsa->encrypt($bytTempBytes);

            /* Array.Reverse(bytEncryptedBytes) */
            $bytEncryptedBytes = strrev($bytEncryptedBytes);

            /* sbText.Append(Convert.ToBase64String(bytEncryptedBytes)) */
            $sbText .= base64_encode($bytEncryptedBytes);
        }

        return $sbText;
    }

    /**
     * @param SimpleXMLElement $data
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     */
    public function sendData($data)
    {
      return $this->response = new Response($this, $data);
    }

    public function getEndpoint()
    {
        return $this->liveEndpoint;
    }
}
