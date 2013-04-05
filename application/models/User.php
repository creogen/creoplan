<?php

class Application_Model_User extends Creogen_DomainObject
{
    private $_taskMapper;

    public function __construct()
    {
        $this->_taskMapper = new Application_Model_TaskMapper();
    }

    public static $roles = array(
        'user' => 'Пользователь',
        'admin' => 'Администратор',
    );

    public function getLogin()
    {
        return $this->getProperty('login');
    }

    public function setLogin($login)
    {
        return $this->setProperty('login', $login);
    }

    public function getRole()
    {
        return $this->getProperty('role');
    }

    public function setRole($role)
    {
        return $this->setProperty('role', $role);
    }

    public function setPassword($password)
    {
        $password = sprintf('%s%s', 'creoplanner', $password);
        $password = sha1($password);
        return $this->setProperty('password', $password);
    }

    public function getRoleTitle()
    {
        return self::$roles[$this->getRole()];
    }

    public function getTotalTasks()
    {
        return $this->_taskMapper->countTotalForUser($this->getId());
    }

    public function getNotComplete()
    {
        return $this->_taskMapper->countNotCompleteForUser($this->getId());
    }
}

