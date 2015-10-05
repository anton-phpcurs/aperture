<?php
/**
 * Created by PhpStorm.
 * User: neonum
 * Date: 04.10.15
 * Time: 00:59
 */

namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class EditForm extends Form
{
    //------------------------------------------------------------------------------------------------------------------
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addInputFilter();
        $this->addElements();
        $this->prepare();

        $this->setAttribute('action', '/accounts/process_login');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('method', 'post');
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addElements()
    {
        //-- Full name --
        $full_name = new Element\Text('full_name');
        $full_name->setAttribute('placeholder', 'Full Name');
        $full_name->setAttribute('class', 'form-control');
        $full_name->setAttribute('id', 'full-name');

        $full_name->setLabel('Full name');
        $full_name->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'full-name'
        ));

        //-- Bio --
        $bio = new Element\Textarea('bio');
        $bio->setAttribute('placeholder', 'Bio');
        $bio->setAttribute('class', 'form-control');
        $bio->setAttribute('id', 'bio');

        $bio->setLabel('Bio');
        $bio->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'bio'
        ));

        //-- Link skype --
        $link_skype = new Element\Text('link_skype');
        $link_skype->setAttribute('placeholder', 'skype_name');
        $link_skype->setAttribute('class', 'form-control');
        $link_skype->setAttribute('id', 'link-skype');

        $link_skype->setLabel('Skype');
        $link_skype->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'link-skype'
        ));

        //-- Link site --
        $link_site = new Element\Text('link_site');
        $link_site->setAttribute('placeholder', 'http://my-site.com');
        $link_site->setAttribute('class', 'form-control');
        $link_site->setAttribute('id', 'link-site');

        $link_site->setLabel('Link my site');
        $link_site->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'link-site'
        ));

        //-- Link vkontakte --
        $link_vk = new Element\Text('link_vk');
        $link_vk->setAttribute('placeholder', 'http://vk.com/user_name');
        $link_vk->setAttribute('class', 'form-control');
        $link_vk->setAttribute('id', 'link-vk');

        $link_vk->setLabel('Link VKontakte');
        $link_vk->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'link-vk'
        ));

        //-- Link facebook --
        $link_fb = new Element\Text('link_fb');
        $link_fb->setAttribute('placeholder', 'https://www.facebook.com/user_name');
        $link_fb->setAttribute('class', 'form-control');
        $link_fb->setAttribute('id', 'link-fb');

        $link_fb->setLabel('Link Facebook');
        $link_fb->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'link-fb'
        ));

        //-- Link twitter --
        $link_tw = new Element\Text('link_tw');
        $link_tw->setAttribute('placeholder', 'https://twitter.com/user_name');
        $link_tw->setAttribute('class', 'form-control');
        $link_tw->setAttribute('id', 'link-tw');

        $link_tw->setLabel('Link Twitter');
        $link_tw->setLabelAttributes(array(
            'class' => 'control-label',
            'for' => 'flink-tw'
        ));

        //-- Submit --
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-success');
        $submit->setValue('Send');

        // Binding elements
        $this->add($full_name);
        $this->add($bio);
        $this->add($link_skype);
        $this->add($link_site);
        $this->add($link_vk);
        $this->add($link_fb);
        $this->add($link_tw);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function addInputFilter()
    {
    }
}