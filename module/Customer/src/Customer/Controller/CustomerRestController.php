<?php

namespace Customer\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Customer\Model\Customer;
use Customer\Form\CustomerForm;
use Zend\View\Model\JsonModel;

class CustomerRestController extends AbstractRestfulController
{
    protected $customerTable;

    /**
     * This is the function to get all or a queried collection
     * of customers
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $params = $this->getRequest()->getQuery();
        $results = $this->getCustomerTable()->fetchAll($params->toArray());
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }

        return  new JsonModel(array('data' => $data));
    }


    /**
     * Get an individual customer
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function get($id)
    {
        $customer = $this->getCustomerTable()->getCustomer($id);

        return new JsonModel(array('data' => $customer));
    }


    /**
     * Create a new customer
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        $form = new CustomerForm();
        $customer = new Customer();
        $form->setInputFilter($customer->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $customer->exchangeArray($form->getData());
            $id = $this->getCustomerTable()->saveCustomer($customer);
        }  else {
            return new JsonModel(array('errors' => $form->getMessages()));
        }

        return new JsonModel(array(
            'data' => $this->getCustomerTable()->getCustomer($id)
        ));
    }


    /**
     * Update an existing customer
     * @param mixed $id
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function update($id, $data)
    {
        $data['id'] = $id;
        $customer = $this->getCustomerTable()->getCustomer($id);
        $form = new CustomerForm();
        $form->bind($customer);
        $form->setInputFilter($customer->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getCustomerTable()->saveCustomer($form->getData());
        } else {
            return new JsonModel(array('errors' => $form->getMessages()));
        }

        return new JsonModel(array(
            'data' => $this->getCustomerTable()->getCustomer($id)
        ));
    }


    /**
     * Delete a customer
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function delete($id)
    {
        $this->getCustomerTable()->deleteCustomer($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }


    /**
     * Get our table
     * @return array|object
     */
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Customer\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}