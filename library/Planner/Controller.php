<?php

class Planner_Controller extends Zend_Controller_Action
{
    public function init()
    {
        $user = $this->_helper->auth();
        if (!$user) {
            $this->_redirect('/login/');
        }
    }
}