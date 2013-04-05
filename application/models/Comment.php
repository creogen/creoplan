<?php

class Application_Model_Comment extends Creogen_DomainObject
{
    private $_userMapper;

    public function __construct()
    {
        $this->_userMapper = new Application_Model_UserMapper();
    }

    public function getTaskId()
    {
        return $this->getProperty('task_id');
    }

    public function getUserId()
    {
        return $this->getProperty('user_id');
    }

    public function getUserLogin()
    {
        $user = $this->_userMapper->find($this->getUserId());
        return $user->getLogin();
    }

    public function getDate()
    {
        return $this->getProperty('date');
    }

    public function getText()
    {
        return $this->getProperty('text');
    }

    public function setTaskId($taskId)
    {
        return $this->setProperty('task_id', $taskId);
    }

    public function setUserId($userId)
    {
        return $this->setProperty('user_id', $userId);
    }

}

