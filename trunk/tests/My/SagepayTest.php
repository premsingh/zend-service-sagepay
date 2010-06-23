<?php

class My_SagepayTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        
    }

    /**
     * @expectedException My_Sagepay_Exception
     */
    public function testSetOptionsWithoutVendorThrows()
    {
        $sagepay = new My_Sagepay(array(
            'currency' => 'GBP'
        ));
    }


    /**
     * @expectedException My_Sagepay_Exception
     */
    public function testSetOptionsWithoutCurrencyThrows()
    {
        $sagepay = new My_Sagepay(array(
            'vendor' => 'testVendor'
        ));
    }
    
}