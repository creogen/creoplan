<?php

class Application_Model_Project extends Creogen_DomainObject
{
    private $_userMapper;
    private $_taskMapper;

    public function __construct()
    {
        $this->_userMapper = new Application_Model_UserMapper();
        $this->_taskMapper = new Application_Model_TaskMapper();
    }

    public function getTitle()
    {
        return $this->getProperty('title');
    }

    public function getManagerId()
    {
        return $this->getProperty('manager_id');
    }

    public function getManagerLogin()
    {
        $user = $this->_userMapper->find($this->getManagerId());
        return $user ? $user->getLogin() : 'Не найден';
    }

    public function getTotalTasks()
    {
        return $this->_taskMapper->countTotalForProject($this->getId());
    }

    public function getNotComplete()
    {
        return $this->_taskMapper->countNotCompleteForProject($this->getId());
    }

    public function setTitle($title)
    {
        return $this->setProperty('title', $title);
    }

    public function setManagerId($managerId)
    {
        return $this->setProperty('manager_id', $managerId);
    }
}

