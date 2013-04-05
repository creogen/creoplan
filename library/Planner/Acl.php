<?php

class Planner_Acl extends Zend_Acl
{
    public function __construct()
    {
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'user');
    }
}