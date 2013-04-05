<?php

class Planner_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{
    private $_user = false;

    public function __construct()
    {

    }

    /**
     * @return Application_Model_User|bool
     */
    public function direct()
    {
        $auth = Zend_Auth::getInstance();

        if (!$auth->hasIdentity()) {
            $this->_user = false;
            return false;
        }

        if (!$this->_user) {
            $userData = $auth->getIdentity();
            $this->_user = $this->getUser($userData);
        }

        return $this->_user;
    }

    private function getUser($userData)
    {
        $userMapper = new Application_Model_UserMapper();
        return $userMapper->find($userData->id);
    }
}