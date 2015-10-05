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

        $this->setAttribute('action', '/files/save');
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
                //->attach(new Validator\File\MimeType(('image', 'audio')))
                ->attach(new Validator\File\Size(array('max' => 250480)))
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

        //Mime type: https://ru.wikipedia.org/wiki/%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA_MIME-%D1%82%D0%B8%D0%BF%D0%BE%D0%B2

/*        $file = new InputFilter\Input('file');
#        $file->getValidatorChain()->attachByName('filesize',      array('max' => 50480));
#            ->attachByName('filesize',      array('max' => 50480))
#            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png,image/jpeg,image/gif,image/tiff'))  //,video/mpeg, video/mp4'
#            ->attachByName('fileimagesize', array('minWidth' => 600, 'minHeight' => 600));

        $file->getValidatorChain()->attach(new Validator\FileInput(array(
            'filesize' => array('max' => 50480)
        )));

        //-- Bind filters ---------------------------------
        $inputFilters = new InputFilter\InputFilter();
        $inputFilters->add($file);
        $this->setInputFilter($inputFilters);

*/
/*        $iF = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('file');
        $fileInput->setRequired(true);

        $fileInput->getValidatorChain()
//            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png'));
//            ->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));
/*
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './uploads/avatar.png',
                'randomize' => true,
            )
        );
*/
       // $iF->add($fileInput);
       // $this->setInputFilter($iF);
    }
}