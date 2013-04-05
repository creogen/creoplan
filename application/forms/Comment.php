<?php

class Application_Form_Comment extends Creogen_Form
{

    public function init()
    {
        $this->addElement(
            new Zend_Form_Element_Textarea(
                'text',
                array(
                    'cols' => 80,
                    'rows' => 10,
                    'label' => 'Комментарий',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->viewScript = '_forms/comment.phtml';
    }


}

