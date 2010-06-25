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
     * Test that a Zend_Config File can be used to set options
     */
    public function testSetOptionsWithZendConfig()
    {
        $params = array(
            'currency' => 'GBP',
            'vendor'   => 'myVendor',
        );

        $sagepay = new My_Sagepay(new Zend_Config($params));
    }

    /**
     * Test log key sets the log
     */
    public function testLogOptionSetsLog()
    {
        $params = array(
            'currency' => 'GBP',
            'vendor'   => 'myVendor',
            'log'      => new Zend_Log(new Zend_Log_Writer_Firebug()),
        );
        $sagepay = new My_Sagepay($params);

        $log = $sagepay->getLog();

        $this->assertThat(
            $log,
            $this->isInstanceOf('Zend_Log')
        );
    }

}