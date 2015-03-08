<?php
// module/Customer/test/CustomerTest/Model/CustomerTableTest.php:
namespace Customer\Model;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
class CustomerTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllCustomers()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
            array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with()
            ->will($this->returnValue($resultSet));
        $CustomerTable = new CustomerTable($mockTableGateway);
        $this->assertSame($resultSet, $CustomerTable->fetchAll());
    }
    public function testCanRetrieveAnCustomerByItsId()
    {
        $Customer = new Customer();
        $Customer->exchangeArray(array('id'     => 123,
            'artist' => 'The Military Wives',
            'title'  => 'In My Dreams'));
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array($Customer));
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 123))
            ->will($this->returnValue($resultSet));
        $CustomerTable = new CustomerTable($mockTableGateway);
        $this->assertSame($Customer, $CustomerTable->getCustomer(123));
    }
    public function testCanDeleteAnCustomerByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('delete')
            ->with(array('id' => 123));
        $CustomerTable = new CustomerTable($mockTableGateway);
        $CustomerTable->deleteCustomer(123);
    }
    public function testSaveCustomerWillInsertNewCustomersIfTheyDontAlreadyHaveAnId()
    {
        $CustomerData = array('artist' => 'The Military Wives', 'title' => 'In My Dreams');
        $Customer     = new Customer();
        $Customer->exchangeArray($CustomerData);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($CustomerData);
        $CustomerTable = new CustomerTable($mockTableGateway);
        $CustomerTable->saveCustomer($Customer);
    }
    public function testSaveCustomerWillUpdateExistingCustomersIfTheyAlreadyHaveAnId()
    {
        $CustomerData = array('id' => 123, 'artist' => 'The Military Wives', 'title' => 'In My Dreams');
        $Customer     = new Customer();
        $Customer->exchangeArray($CustomerData);
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array($Customer));
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
            array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 123))
            ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with(array('artist' => 'The Military Wives', 'title' => 'In My Dreams'),
                array('id' => 123));
        $CustomerTable = new CustomerTable($mockTableGateway);
        $CustomerTable->saveCustomer($Customer);
    }
    public function testExceptionIsThrownWhenGettingNonexistentCustomer()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array());
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 123))
            ->will($this->returnValue($resultSet));
        $CustomerTable = new CustomerTable($mockTableGateway);
        try
        {
            $CustomerTable->getCustomer(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }
        $this->fail('Expected exception was not thrown');
    }
}