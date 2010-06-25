<?php

class My_SagepayTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_sagepay = new My_Sagepay(array(
            'vendor'    => 'myVendor'
        ));
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
        $this->assertEquals('GBP', $sagepay->getCurrency());
        $this->assertEquals('myVendor', $sagepay->getVendor());
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

    /**
     * Test set currency
     */
    public function testSetCurrency()
    {
        $this->_sagepay->setCurrency('EUR');
        $this->assertEquals('EUR', $this->_sagepay->getCurrency());
    }

    /**
     * Test set vps protocol
     */
    public function testSetVpsProtocol()
    {
        $this->_sagepay->setVpsProtocol('2.52');
        $this->assertEquals('2.52', $this->_sagepay->getVpsProtocol());
    }

    /**
     * Test set test mode
     */
    public function testSetTestMode()
    {
        $this->_sagepay->setTestMode(true);
        $this->assertEquals(true, $this->_sagepay->isTestMode());
    }

    /**
     * Test set simulator mode
     */
    public function testSetSimulator()
    {
        $this->_sagepay->setSimulator(true);
        $this->assertEquals(true, $this->_sagepay->isSimulator());
    }

    /**
     * Test set vendor
     */
    public function testSetVendor()
    {
        $this->_sagepay->setVendor('superVendor');
        $this->assertEquals('superVendor', $this->_sagepay->getVendor());
    }
    
    /**
     * Test that we always have a HTTP client, a default if none provided
     */
    public function testGetHttpClientAlwaysReturnsAClient()
    {
        $client = $this->_sagepay->getHttpClient();
        
        $this->assertThat(
            $client,
            $this->isInstanceOf('Zend_Http_Client')
        );
    }

    /**
     * Test that we always have a logger, even if it is the null object
     */
    public function testGetLogAlwaysReturnsALog()
    {
        $client = $this->_sagepay->getLog();

        $this->assertThat(
            $client,
            $this->isInstanceOf('Zend_Log')
        );
    }

    /**
     * Test that simulator and test mode are not enabled by default
     */
    public function testSimulatorAndTestModeAreOffByDefault()
    {
        $this->assertFalse($this->_sagepay->isSimulator());
        $this->assertFalse($this->_sagepay->isTestMode());
    }

}