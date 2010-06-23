<?php

/**
 * @category   My
 * @package    My_Sagepay
 * @subpackage Transaction
 */
class My_Sagepay
{
    const ENDPOINT_SIMULATOR = 'https://test.sagepay.com/Simulator/VSPDirectGateway.asp?Service=%s';
    const ENDPOINT_TEST      = 'https://test.sagepay.com/gateway/service/%s.vsp';
    const ENDPOINT_LIVE      = 'https://live.sagepay.com/gateway/service/%s.vsp';

    const TXTYPE_PAYMENT = 'PAYMENT';
    const TXTYPE_REPEAT  = 'REPEAT';

    /**
     * Use live or test mode. Ignored if using simulator
     * @var bool
     */
    protected $_testMode = false;

    /**
     * Use the simulator
     * @var bool
     */
    protected $_simulator = false;

    /**
     *
     * @var string
     */
    protected $_vpsProtocol = '2.23';

    /**
     *
     * @var string
     */
    protected $_amount;

    /**
     *
     * @var string
     */
    protected $_vendor;

    /**
     *
     * @var string
     */
    protected $_vendorTxCode;

    /**
     *
     * @var string
     */
    protected $_currency;

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     *
     * @var Zend_Http_Client
     */
    protected $_httpClient;

    /**
     *
     * @param Zend_Config|array $options
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        $this->setOptions($options);

        $this->_setDefaultHttpClient();
    }

    /**
     *
     * @param  array      $options
     * @return My_Sagepay Provides fluent interface
     */
    public function setOptions(array $options = array())
    {
        $methods = get_class_methods($this);

        $this->_checkRequiredOptions($options);

        foreach ($options as $key => $value) {

            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }


    /**
     * Check for config options that are mandatory (according to Sagepay)
     * Throw exceptions if any are missing.
     *
     * @param array $options
     * @throws Zend_Db_Adapter_Exception
     */
    protected function _checkRequiredOptions(array $options)
    {
        // we need at least a dbname
        if (! array_key_exists('vendor', $options)) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'My/Sagepay/Exception.php';
            throw new My_Sagepay_Exception("Configuration array must have a key for 'vendor'");
        }
        if (! array_key_exists('currency', $options)) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'My/Sagepay/Exception.php';
            throw new My_Sagepay_Exception("Configuration array must have a key for 'currency'");
        }
    }

    /**
     *
     * @param  string     $value
     * @return My_Sagepay Provides fluent interface
     */
    public function setVpsProtocol($value)
    {
        $this->_vpsProtocol = (string) $value;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getVpsProtocol()
    {
        return $this->_vpsProtocol;
    }

    /**
     *
     * @param  string     $value
     * @return My_Sagepay Provides fluent interface
     */
    public function setVendor($value)
    {
        $this->_vendor = (string) $value;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getVendor()
    {
        return $this->_vendor;
    }

    /**
     *
     * @param  bool     $value
     * @return My_Sagepay Provides fluent interface
     */
    public function setTestMode($value)
    {
        $this->_testMode = (bool) $value;
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isTestMode()
    {
        return $this->_testMode;
    }

    /**
     *
     * @param  bool     $value
     * @return My_Sagepay Provides fluent interface
     */
    public function setSimulator($value)
    {
        $this->_simulator = (bool) $value;
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isSimulator()
    {
        return $this->_simulator;
    }

    /**
     * @param  string $type
     * @return string
     */
    public function getEndpoint($type)
    {
        if ($this->_simulator){
            return sprintf(self::ENDPOINT_SIMULATOR, $type);
        }

        $endpoint = $this->_testMode ? self::ENDPOINT_TEST : self::ENDPOINT_LIVE;
        return sprintf($endpoint, $type);
    }

    /**
     *
     * @param  string     $value
     * @return My_Sagepay Provides fluent interface
     */
    public function setCurrency($value)
    {
        $this->_currency = (string) $value;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * Return fields common to all transactions
     * @return array Postdata
     */
    protected function _getPostData()
    {
        $data = array(
            'Vendor'       => $this->getVendor(),
            'VPSProtocol'  => $this->getVpsProtocol(),
            'Currency'     => $this->getCurrency(),
        );
        return $data;
    }

    /**
     * Get the HTTP client to be used for this service
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        return $this->_httpClient;
    }

    /**
     * Set the HTTP client to be used for this service
     *
     * @param  Zend_Http_Client $client
     * @return My_Sagepay Provides fluent interface
     */
    public function setHttpClient(Zend_Http_Client $client)
    {
        $this->_httpClient = $client;
        return $this;
    }

    /**
     * Get the Log to be used for this service
     * Defaults to a null writer to stub out the log
     *
     * @return Zend_Log
     */
    public function getLog()
    {
        if ( ! $this->_log instanceOf Zend_Log ) {
            require_once 'Zend/Log.php';
            require_once 'Zend/Log/Writer/Null.php';
            $writer = new Zend_Log_Writer_Null();
            $this->setLog(new Zend_Log($writer));
        }
        
        return $this->_log;
    }

    /**
     * Set the Log to be used for this service
     *
     * @param  Zend_Log $log
     * @return My_Sagepay Provides fluent interface
     */
    public function setLog(Zend_Log $log)
    {
        $this->_log = $log;
        return $this;
    }

    /**
     * If not currently set, a default client will be created.
     *
     * @return void
     */
    protected function _setDefaultHttpClient()
    {
        if( ! $this->_httpClient instanceOf Zend_Http_Client ) {
            require_once 'Zend/Http/Client.php';
            $this->setHttpClient( new Zend_Http_Client() );
        }
    }

    /**
     *
     * @param  string                       $amount
     * @param  string                       $description
     * @param  string                       $reference             Order ID or Ref
     * @param  My_Sagepay_Data_CreditCard   $creditCard
     * @param  My_Sagepay_Data_Address      $billingAddress
     * @param  My_Sagepay_Data_Address      $shippingAddress
     * @return My_Sagepay_Response
     */
    public function doPayment($amount, $description, $reference,
            My_Sagepay_Data_CreditCard $creditCard,
            My_Sagepay_Data_Address $billingAddress = null,
            My_Sagepay_Data_Address $shippingAddress = null)
    {
        $data = array(
            'Amount'       => $amount,
            'Description'  => $description,
            'VendorTxCode' => $reference,
            'TxType'       => self::TXTYPE_PAYMENT,
        );

        $data += $creditCard->toNvp();
        if ($billingAddress){
            $data += $billingAddress->toNvp('Billing');
        }
        if ($shippingAddress){
            $data += $shippingAddress->toNvp('Delivery');
        }
        return $this->_doRequest('vspdirect-register', $data);
    }

    /**
     *
     * @return My_Sagepay_Response
     */
    public function doRepeat($amount, $description, $reference,
            $relatedTransactionId, $relatedReference, $relatedSecurityKey,
            $relatedAuthNo)
    {
        $data = array(
            'Amount'              => $amount,
            'Description'         => $description,
            'VendorTxCode'        => $reference,
            'TxType'              => self::TXTYPE_REPEAT,
            'RelatedVPSTxId'      => $relatedTransactionId,
            'RelatedVendorTxCode' => $relatedReference,
            'RelatedSecurityKey'  => $relatedSecurityKey,
            'RelatedTxAuthNo'     => $relatedAuthNo,

        );

        return $this->_doRequest('repeat', $data);
    }

    /**
     *
     * @param string $type Transaction type
     * @param array  $data Transaction data
     * @return My_Sagepay_Response
     */
    protected function _doRequest($type, array $data)
    {
        $postData = $this->_getPostData() + $data;
        $endpoint = $this->getEndpoint($type);

        $this->getLog()->info('POST request to ' . $endpoint);
        $this->getLog()->info($postData);

        $client = $this->getHttpClient();
        $client->setUri($endpoint);
        $client->setMethod(Zend_Http_Client::POST);
        $client->setParameterPost($postData);
        $responseBody = $client->request()->getBody();

        $response = new My_Sagepay_Response($responseBody);
        $this->getLog()->info($response->getNameValuePairs());

        if ($response->isError()){
            throw new My_Sagepay_Exception($response->getErrorMessage());
        }

        return $response;
    }
}