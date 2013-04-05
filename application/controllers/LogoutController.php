<?php

class LogoutController extends Planner_Controller
{
    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/');
    }
}

