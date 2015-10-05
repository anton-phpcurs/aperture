<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 02.10.15
 * Time: 21:29
 */

namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/accounts/signin');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- Profile name --
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

        //-- Password --
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

        //-- Submit --
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        // Binding elements
        $this->add($login);
        $this->add($password);
        $this->add($submit);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
    }
}