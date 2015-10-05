<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 03.10.15
 * Time: 00:17
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

use Zend\InputFilter;
use Zend\Validator;

class EmailForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/accounts/email');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- E-mail current ------------------------------------
        $email_current = new Element\Email('email_current');
        $email_current->setAttribute('readonly', 'true');
        $email_current->setAttribute('class', 'form-control');
        $email_current->setAttribute('id', 'email-old');

        $email_current->setLabel('Current');
        $email_current->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'email-current'
        ));

        //-- E-mail new ---------------------------------------
        $email = new Element\Email('email');
        $email->setAttribute('placeholder', 'example@example.com');
        $email->setAttribute('required', 'true');
        $email->setAttribute('class', 'form-control');
        $email->setAttribute('id', 'email');

        $email->setLabel('New');
        $email->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'email'
        ));

        //-- Submit -------------------------------------
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        // Binding elements
        $this->add($email_current);
        $this->add($email);
        $this->add($submit);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
        //-- E-mail new --------------------------------------
        $email = new InputFilter\Input('email');
        $email
            ->getValidatorChain()
            ->attach(new Validator\EmailAddress(array(
                'domain' => true
            )));

        //-- Bind filters ---------------------------------
        $inputFilters = new InputFilter\InputFilter();
        $inputFilters->add($email);

        $this->setInputFilter($inputFilters);
    }
}