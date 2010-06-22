<?php

/**
 * @category My
 * @package  My_Sagepay
 */
class My_Sagepay_Response
{
    const STATUS_OK          = 'OK';
    const STATUS_MALFORMED   = 'MALFORMED';
    const STATUS_NOTAUTHED   = 'NOTAUTHED';
    const STATUS_REJECTED    = 'REJECTED';
    const STATUS_ERROR       = 'ERROR';
    const STATUS_INVALID     = 'INVALID';
    const STATUS_PPREDIRECT  = 'PPREDIRECT';
    const STATUS_3DAUTH      = '3DAUTH';
    
    /**
     *
     * @var array
     */
    protected $_data = array();

    /**
     * 
     * @param string $response 
     */
    public function __construct($response)
    {
        $this->_parseNameValuePairs($response);
    }

    /**
     *
     * @param string $response
     */
    protected function _parseNameValuePairs($response)
    {
        $pairs = explode("\n", trim($response));
        
        foreach ($pairs as $pair){
            list ($key, $value) = explode('=', $pair);
            $this->_data[trim($key)] = trim($value);
        }
    }

    /**
     *
     * @return bool
     */
    public function isError()
    {
        if (self::STATUS_OK !== $this->_data['Status']){
            return true;
        }
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return !empty($this->_data['StatusDetail']) ? $this->_data['StatusDetail'] : null;
    }

    /**
     *
     * @return array
     */
    public function getNameValuePairs()
    {
        return $this->_data;
    }

    /**
     *
     * @return string
     */
    public function getTransactionId()
    {
        if (!isset($this->_data['VPSTxId'])){
            return false;
        }
        return $this->_data['VPSTxId'];
    }

    /**
     *
     * @return string
     */
    public function getSecurityKey()
    {
        if (!isset($this->_data['SecurityKey'])){
            return false;
        }
        return $this->_data['SecurityKey'];
    }

    /**
     *
     * @return string
     */
    public function getTransactionAuthNo()
    {
        if (!isset($this->_data['TxAuthNo'])){
            return false;
        }
        return $this->_data['TxAuthNo'];
    }

}