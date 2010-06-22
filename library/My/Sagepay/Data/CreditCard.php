<?php

/**
 * CreditCard value object
 *
 * @category   My
 * @package    My_Sagepay
 * @subpackage Data
 */
class My_Sagepay_Data_CreditCard
{
    const TYPE_VISA             = 'VISA';
    const TYPE_MASTERCARD       = 'MC';
    const TYPE_DISCOVERY        = 'DC';
    const TYPE_AMERICANEXPRESS  = 'AMEX';
    const TYPE_SOLO             = 'SOLO';
    const TYPE_LASER            = 'LASER';
    const TYPE_MAESTRO          = 'MAESTRO';
    
    /**
     *
     *
     * @var string
     */
    protected $_type;

    /**
     *
     *
     * @var int
     */
    protected $_cardNumber;

    /**
     *
     *
     * @var string
     */
    protected $_cardHolder;

    /**
     *
     *
     * @var string
     */
    protected $_expiration;

    /**
     *
     *
     * @var string
     */
    protected $_cvv2;

    /**
     *
     *
     * @var string
     */
    protected $_startDate;

    /**
     *
     *
     * @var int
     */
    protected $_issueNumber;

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
    }

    /**
     *
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {

            $method = 'set' . ucfirst($this->_normalizeKey($key));
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Converts a property to option, e.g. user_title -> userTitle
     * @param  string $key
     * @return string
     */
    protected function _normalizeKey($key)
    {
        $option = str_replace('_', ' ', strtolower($key));
        $option = str_replace(' ', '', ucwords($option));
        return $option;
    }

    /**
     *
     *
     * @param string $type
     *
     * @return void
     */
    public function setCardType($type)
    {
        $this->_type = $type;
    }

    /**
     *
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->_type;
    }

    /**
     *
     *
     * @param string $number
     *
     * @return void
     */
    public function setCardNumber($number)
    {
        $this->_cardNumber = $number;
    }

    /**
     *
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->_cardNumber;
    }

    /**
     *
     *
     * @param string $holder
     *
     * @return void
     */
    public function setCardHolder($holder)
    {
        $this->_cardHolder = $holder;
    }

    /**
     *
     *
     * @return string
     */
    public function getCardHolder()
    {
        return $this->_cardHolder;
    }

    /**
     *
     *
     * @param string $date
     *
     * @return void
     */
    public function setExpiryDate($date)
    {
        $this->_expiration = $date;
    }

    /**
     *
     *
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->_expiration;
    }

    /**
     *
     *
     * @param string $cvv2
     *
     * @return void
     */
    public function setCvv2($cvv2)
    {
        $this->_cvv2 = $cvv2;
    }

    /**
     *
     *
     * @return string
     */
    public function getCvv2()
    {
        return $this->_cvv2;
    }

    /**
     *
     *
     * @param string $date
     *
     * @return void
     */
    public function setStartDate($date)
    {
        $this->_startDate = $date;
    }

    /**
     *
     *
     * @return string
     */
    public function getStartdate()
    {
        return $this->_startdate;
    }

    /**
     *
     *
     * @param string $issueNumber
     *
     * @return void
     */
    public function setIssueNumber($issueNumber)
    {
        $this->_issueNumber = $issueNumber;
    }

    /**
     *
     *
     * @return array
     */
    public function toNvp()
    {
        $data = array();
        $data['CardType']       = $this->_type;
        $data['CardNumber']     = $this->_cardNumber;
        $data['ExpiryDate']     = $this->_expiration;
        $data['CV2']            = $this->_cvv2;
        $data['StartDate']      = $this->_startDate;
        $data['IssueNumber']    = $this->_issueNumber;
        $data['CardHolder']     = $this->_cardHolder;

        return array_filter($data);
    }
}