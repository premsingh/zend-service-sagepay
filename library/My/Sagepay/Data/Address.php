<?php

/**
 * Customer address value object
 *
 * @category   My
 * @package    My_Sagepay
 * @subpackage Data
 */
class My_Sagepay_Data_Address
{

    /**
     *
     * @var string
     */
    protected $_surname;

    /**
     *
     * @var string
     */
    protected $_firstnames;

    /**
     *
     * @var string
     */
    protected $_address1;

    /**
     *
     * @var string
     */
    protected $_address2;

    /**
     *
     * @var string
     */
    protected $_city;

    /**
     *
     * @var string
     */
    protected $_postcode;

    /**
     *
     * @var string
     */
    protected $_country;

    /**
     *
     * @param Zend_Config|array $options
     */
    public function __construct($options = array())
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
     * @param  string $surname
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setSurname($surname)
    {
        $this->_surname = $surname;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->_surname;
    }

    /**
     *
     * @param  string $firstnames
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setFirstnames($firstnames)
    {
        $this->_firstnames = $firstnames;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getFirstnames()
    {
        return $this->_firstnames;
    }

    /**
     *
     * @param  string $address
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setAddress1($address)
    {
        $this->_address1 = $address;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->_address1;
    }

    /**
     *
     * @param  string $address
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setAddress2($address)
    {
        $this->_address2 = $address;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->_address2;
    }

    /**
     *
     * @param  string $city
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setCity($city)
    {
        $this->_city = $city;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     *
     * @param  string $postcode
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setPostcode($postcode)
    {
        $this->_postcode = $postcode;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->_postcode;
    }

    /**
     *
     * @param  string $country
     * @return My_Sagepay_Data_Address Provides fluent interface
     */
    public function setCountry($country)
    {
        $this->_country = $country;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     *
     * @param  string $prefix
     * @return array
     */
    public function toNvp($prefix = 'Billing')
    {
        $data = array();
        $data[$prefix . 'Surname']    = $this->_surname;
        $data[$prefix . 'Firstnames'] = $this->_firstnames;
        $data[$prefix . 'Address1']   = $this->_address1;
        $data[$prefix . 'Address2']   = $this->_address2;
        $data[$prefix . 'City']       = $this->_city;
        $data[$prefix . 'Postcode']   = $this->_postcode;
        $data[$prefix . 'Country']    = $this->_country;
        return array_filter( $data );
    }
}