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

class UploadForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/files/edit');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- File --
        $file = new Element\File('file');
        $file->setAttribute('placeholder', 'profile_name');
        $file->setAttribute('required', 'true');
        $file->setAttribute('class', 'form-control');
        $file->setAttribute('id', 'file');

        $file->setLabel('Profile name');
        $file->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'file'
        ));

        //-- Submit --
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        //-- Binding elements --
        $this->add($file);
        $this->add($submit);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
        $file = new InputFilter\Input('file');
        $file->getValidatorChain()
                ->attach(new Validator\File\Size(array('max' => 3100000)))
                ->attach(new Validator\File\ImageSize(array(
                    'minWidth' => 600, 'minHeight' => 600,
                )));

        $file->getFilterChain()->attachByName('filerenameupload', array(
            'target'    => './public/uploads/',
            'randomize' => true,
        ));

        //-- Bind filters ---------------------------------
        $inputFilters = new InputFilter\InputFilter();
        $inputFilters->add($file);
        $this->setInputFilter($inputFilters);
    }
}