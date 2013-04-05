<?php

class Application_Model_File extends Creogen_DomainObject
{
    public function getTaskId()
    {
        return $this->getProperty('task_id');
    }

    public function getFilePath()
    {
        return $this->getProperty('file_path');
    }

    public function getUserId()
    {
        return $this->getProperty('user_id');
    }

    public function getUploadDate()
    {
        return $this->getProperty('upload_date');
    }

    public function getUploadDateFormatted()
    {
        return date("d.m.Y H:i", $this->getUploadDate());
    }
}

