<?php

class Application_Form_Project extends Creogen_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement(
            new Zend_Form_Element_Text(
                'title',
                array(
                    'required' => true,
                    'label' => 'Название проекта',
                    'size' => '80',
                    'class' => 'iText',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Select(
                'manager_id',
                array(
                    'multiOptions' => $this->getUsers(),
                    'required' => true,
                    'label' => 'Менеджер проекта',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->viewScript = '_forms/project.phtml';
    }

    private function getUsers()
    {
        $users = array(0 => 'Выберите пользователя');
        $userMapper = new Application_Model_UserMapper();
        $userObjects = $userMapper->fetchAll();
        foreach ($userObjects as $u) {
            $users[$u->getId()] = $u->getLogin();
        }
        return $users;
    }
}

