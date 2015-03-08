<?php
// module/Customer/src/Customer/Model/Customer.php:
namespace Customer\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Customer implements InputFilterAwareInterface
{
    public $id;
    public $nameFirst;
    public $nameLast;
    public $email;
    public $phone;
    public $twitter;
    public $facebook;
    public $address;

    protected $inputFilter;

    /**
     * This is how we hydrate
     * @param $data
     */
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->nameFirst = (isset($data['nameFirst'])) ? $data['nameFirst'] : null;
        $this->nameLast  = (isset($data['nameLast']))  ? $data['nameLast']  : null;
        $this->email  = (isset($data['email']))  ? $data['email']  : null;
        $this->address  = (isset($data['address']))  ? $data['address']  : null;
        $this->phone  = (isset($data['phone']))  ? $data['phone']  : null;
        $this->twitter  = (isset($data['twitter']))  ? $data['twitter']  : null;
        $this->facebook  = (isset($data['facebook']))  ? $data['facebook']  : null;
    }

    // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Get the input filtersd for the customer object
     * @return InputFilter|InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'nameLast',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'EmailAddress',
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}