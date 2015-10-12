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

class ActiveForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/accounts/activation');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- Profile name --
        $keyA = new Element\Text('activation_key');
        $keyA->setAttribute('placeholder', md5('Example'));
        //$keyA->setAttribute('required', 'true');
        $keyA->setAttribute('class', 'form-control');
        $keyA->setAttribute('id', 'activation-key');

        $keyA->setLabel('Activation key');
        $keyA->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'activation-key'
        ));

        //-- Submit --
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        //-- Submit --
        $submit_email = new Element\Submit('submit_email');
        $submit_email->setAttribute('class', 'btn btn-info');
        $submit_email->setValue('Send Email');

        // Binding elements
        $this->add($keyA);
        $this->add($submit);
        $this->add($submit_email);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
    }
}