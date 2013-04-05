<?php

class Application_Form_File extends Creogen_Form
{
    public function init()
    {
        $this->addElement(
            new Zend_Form_Element_Hidden(
                'id',
                array(
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $FileField = new Zend_Form_Element_File('filename',
            array(
                'required' => true,
                'size' => 15,
            )
        );

        $FileField->setDestination(Zend_Registry::get('config')->uploadFiles);

        $this->addElement($FileField);

        $this->viewScript = '_forms/file.phtml';
    }
}

