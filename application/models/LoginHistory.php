<?php

class Application_Model_LoginHistory extends Creogen_DomainObject
{
    public function getUserId()
    {
        return $this->getProperty('user_id');
    }

    public function getTime()
    {
        return $this->getProperty('time');
    }
}

