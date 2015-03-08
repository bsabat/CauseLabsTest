<?php
// module/Customer/src/Customer/Model/CustomerTable.php:
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;

class CustomerTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get a collection of customers based on query
     * @param null $params
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($params = null)
    {
        // we remove the access key from the params
        // so we dont query on that.  This can probably be
        // done in the aclCheck so all functions can rely on that
        if(array_key_exists("key", $params)) {
            unset($params['key']);
        }
        $select = new Select();
        $where = [];

        // Loop through and build our query
        foreach($params as $key => $vsl) {
            if ($key == 'nameLast') {
                // This could arguably be put in another object that
                // checks to see if the field has any special requirements.

                // I chose to match on the beginning on the last name.  If this were
                // a real task I would have clarified desired functionality here.
                $where[] = "nameLast LIKE '" . $vsl . "%'";
            } else {
                // Using the query params in this way does not support different
                // operands.  This will only support =
                $where[] = "{$key} = '" . $vsl . "'";
            }
        }

        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }

    /**
     * Get a single customer
     * @param $id
     * @return array|\ArrayObject|null
     * @throws \Exception
     */
    public function getCustomer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     * Save a customer.  Insert if new, update if old
     * @param Customer $customer
     * @throws \Exception
     */
    public function saveCustomer(Customer $customer)
    {
        $data = array(
            'nameFirst' => $customer->nameFirst,
            'nameLast'  => $customer->nameLast,
            'email'  => $customer->email,
            'phone'  => $customer->phone,
            'address'  => $customer->address,
            'twitter'  => $customer->nameLast,
            'facebook'  => $customer->nameLast,

        );

        $id = (int)$customer->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            // Now pass back the id of the newly inserted
            return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getCustomer($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    /**
     * Delete the customer
     * @param $id
     */
    public function deleteCustomer($id)
    {
        // Here you would probably update and set a flag in the db
        // rather than delete.
        $this->tableGateway->delete(array('id' => $id));
    }
}