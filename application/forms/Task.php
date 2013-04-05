<?php

class Application_Form_Task extends Creogen_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement(
            new Zend_Form_Element_Select(
                'project_id',
                array(
                    'multiOptions' => $this->getProjects(),
                    'required' => true,
                    'label' => 'Проект',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Select(
                'type',
                array(
                    'multiOptions' => $this->getTypes(),
                    'required' => true,
                    'label' => 'Тип задачи',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Text(
                'title',
                array(
                    'required' => true,
                    'label' => 'Название',
                    'size' => '80',
                    'class' => 'iText',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Select(
                'priority',
                array(
                    'multiOptions' => $this->getPriorities(),
                    'required' => true,
                    'label' => 'Приоритет',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Select(
                'assigned_to',
                array(
                    'multiOptions' => $this->getUsers(),
                    'required' => true,
                    'label' => 'Поручить',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->addElement(
            new Zend_Form_Element_Textarea(
                'text',
                array(
                    'cols' => 80,
                    'rows' => 10,
                    'label' => 'Описание',
                    'decorators' => array('ViewHelper'),
                )
            )
        );

        $this->viewScript = '_forms/task.phtml';
    }

    private function getProjects()
    {
        $projects = array(0 => 'Выберите проект...');
        $projectMapper = new Application_Model_ProjectMapper();
        $projectObjects = $projectMapper->fetchAll();
        foreach ($projectObjects as $p) {
            $projects[$p->getId()] = $p->getTitle();
        }
        return $projects;
    }

    private function getTypes()
    {
        return array_merge(
            array(0 => 'Выберите тип задания...'),
            Application_Model_Task::$types
        );
    }

    private function getPriorities()
    {
        return array_merge(
            array(0 => 'Выберите приоритет...'),
            Application_Model_Task::$priorities
        );
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

