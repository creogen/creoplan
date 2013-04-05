<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initConfig()
    {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }
}

