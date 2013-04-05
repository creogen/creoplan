<?php

class Creogen_Form extends Zend_Form
{
    protected $viewScript;

    public function loadDefaultDecorators()
    {
        if ($this->viewScript) {
            $this->setDecorators(array(
                array('ViewScript', array('viewScript' => $this->viewScript))
            ));
        } else {
            $this->setDecorators(array(
                'FormElements',
                'Fieldset',
                'Form',
            ));
        }
    }
}