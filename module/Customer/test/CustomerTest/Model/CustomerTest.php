<?php
// module/Customer/test/CustomerTest/Model/CustomerTest.php:
namespace CustomerTest\Model;

use Customer\Model\Customer;

use PHPUnit_Framework_TestCase;

class CustomerTest extends PHPUnit_Framework_TestCase
{
    public function testCustomerInitialState()
    {
        $Customer = new Customer();

        $this->assertNull($Customer->artist, '"artist" should initially be null');
        $this->assertNull($Customer->id, '"id" should initially be null');
        $this->assertNull($Customer->title, '"title" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $Customer = new Customer();
        $data  = array('artist' => 'some artist',
            'id'     => 123,
            'title'  => 'some title');

        $Customer->exchangeArray($data);

        $this->assertSame($data['artist'], $Customer->artist, '"artist" was not set correctly');
        $this->assertSame($data['id'], $Customer->id, '"title" was not set correctly');
        $this->assertSame($data['title'], $Customer->title, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $Customer = new Customer();

        $Customer->exchangeArray(array('artist' => 'some artist',
            'id'     => 123,
            'title'  => 'some title'));
        $Customer->exchangeArray(array());

        $this->assertNull($Customer->artist, '"artist" should have defaulted to null');
        $this->assertNull($Customer->id, '"title" should have defaulted to null');
        $this->assertNull($Customer->title, '"title" should have defaulted to null');
    }
}