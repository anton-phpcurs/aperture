<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 02.10.15
 * Time: 21:29
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

use Zend\InputFilter;
use Zend\Validator;

class RegistrationForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/accounts/signup');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- Profile name ----------------------------------
        $login = new Element\Text('profile_name');
        $login->setAttribute('placeholder', 'profile_name');
        $login->setAttribute('required', 'true');
        $login->setAttribute('class', 'form-control');
        $login->setAttribute('id', 'profile-name');

        $login->setLabel('Profile name');
        $login->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'profile-name'
        ));

        //-- E-mail ---------------------------------------
        $email = new Element\Email('email');
        $email->setAttribute('placeholder', 'example@example.com');
        $email->setAttribute('required', 'true');
        $email->setAttribute('class', 'form-control');
        $email->setAttribute('id', 'email');

        $email->setLabel('E-mail');
        $email->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'email'
        ));

        //-- Password ------------------------------------
        $password = new Element\Password('password');
        $password->setAttribute('placeholder', '*****');
        $password->setAttribute('required', 'true');
        $password->setAttribute('class', 'form-control');
        $password->setAttribute('id', 'password');

        $password->setLabel('Password');
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

        $password_confirm->setLabel('Confirm password');
        $password_confirm->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'confirm_password'
        ));

        //-- Submit -------------------------------------
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        // Binding elements
        $this->add($login);
        $this->add($email);
        $this->add($password);
        $this->add($password_confirm);
        $this->add($submit);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
        //-- Profile name ----------------------------------
        $profile_name = new InputFilter\Input('profile_name');
        $profile_name
            ->getValidatorChain()
            ->attach(new Validator\StringLength(array(
                'encoding' => 'UTF-8',
                'min' => 3,
                'max' => 140,
            )));

        //-- E-mail ---------------------------------------
        $email = new InputFilter\Input('email');
        $email
            ->getValidatorChain()
            ->attach(new Validator\EmailAddress(array(
                'domain' => true
            )));

        //-- Password (confirm) ---------------------------
        $password_confirm = new InputFilter\Input('confirm_password');
        $password_confirm
            ->getValidatorChain()
            ->attach(new Validator\Identical(array(
                'token' => 'password',
            )));

        //-- Bind filters ---------------------------------
        $inputFilters = new InputFilter\InputFilter();

        $inputFilters->add($profile_name);
        $inputFilters->add($email);
        $inputFilters->add($password_confirm);

        $this->setInputFilter($inputFilters);
    }
}
