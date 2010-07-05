<?php

class My_Sagepay_Data_AddressTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test that NVP data doesn't contain values by default
     */
    public function testAddressHasNoValuesByDefault()
    {
        $address = new My_Sagepay_Data_Address();
        $this->assertEquals(0, count($address->toNvp()));
    }

    /**
     * Test surname mutator and accessor
     */
    public function testSetSurname()
    {
        $address = new My_Sagepay_Data_Address();
        $address->setSurname('Smith');

        $this->assertEquals($address->getSurname(), 'Smith');
    }

    /**
     * Test firstnames mutator and accessor
     */
    public function testSetFirstnames()
    {
        $address = new My_Sagepay_Data_Address();
        $address->setFirstnames('John James');

        $this->assertEquals($address->getFirstNames(), 'John James');
    }

}