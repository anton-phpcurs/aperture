<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 04.10.15
 * Time: 0:38
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

use Zend\InputFilter;
use Zend\Validator;

class PasswordForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/accounts/password');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- Password current -----------------------------
        $password_current = new Element\Password('password_current');
        $password_current->setAttribute('placeholder', '*****');
        $password_current->setAttribute('required', 'true');
        $password_current->setAttribute('class', 'form-control');
        $password_current->setAttribute('id', 'password-current');

        $password_current->setLabel('Current');
        $password_current->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'password-current'
        ));

        //-- Password ------------------------------------
        $password = new Element\Password('password');
        $password->setAttribute('placeholder', '*****');
        $password->setAttribute('required', 'true');
        $password->setAttribute('class', 'form-control');
        $password->setAttribute('id', 'password');

        $password->setLabel('New');
        $password->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'password'
        ));

        //-- Password (confirm) ---------------------------
        $password_confirm = new Element\Password('confirm_password');
        $password_confirm->setAttribute('placeholder', '*****');
        $password_confirm->setAttribute('required', 'true');
        $password_confirm->setAttribute('class', 'form-control');
        $password_confirm->setAttribute('id', 'confirm-password');

        $password_confirm->setLabel('Confirm');
        $password_confirm->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'confirm-password'
        ));

        //-- Submit -------------------------------------
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        // Binding elements
        $this->add($password_current);
        $this->add($password);
        $this->add($password_confirm);
        $this->add($submit);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
        //-- Password (confirm) ---------------------------
        $password_confirm = new InputFilter\Input('confirm-password');
        $password_confirm
            ->getValidatorChain()
            ->attach(new Validator\Identical(array(
                'token' => 'password',
            )));

        //-- Bind filters ---------------------------------
        $inputFilters = new InputFilter\InputFilter();
        $inputFilters->add($password_confirm);

        $this->setInputFilter($inputFilters);
    }
}