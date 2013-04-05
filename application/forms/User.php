<?php

class Application_Form_User extends Creogen_Form
{

    public function init()
    {
        $this->addElement(
            new Zend_Form_Element_Text(
                'login',
                array(
                    'required' => true,
                    'label' => 'Логин',
                    'size' => '80',
                    'class' => 'iText',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Password(
                'password',
                array(
                    'required' => false,
                    'label' => 'Пароль',
                    'size' => '80',
                    'class' => 'iText',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Select(
                'role',
                array(
                    'multiOptions' => Application_Model_User::$roles,
                    'required' => true,
                    'label' => 'Роль',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->viewScript = '_forms/user.phtml';
    }


}

