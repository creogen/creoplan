<?php

class Application_Model_Task extends Creogen_DomainObject
{
    private $_userMapper;

    public static $types = array(
        'bug' => 'Баг',
        'error' => 'Ошибка',
        'changes' => 'Изменения',
        'job' => 'Задание'
    );

    public static $priorities = array(
        'low' => 'Низкий',
        'middle' => 'Средний',
        'high' => 'Высокий'
    );

    public function __construct()
    {
        $this->_userMapper = new Application_Model_UserMapper();
    }

    public function getProjectId()
    {
        return $this->getProperty('project_id');
    }

    public function getType()
    {
        return $this->getProperty('type');
    }

    public function getTypeTitle()
    {
        return self::$types[$this->getType()];
    }

    public function getPriority()
    {
        return $this->getProperty('priority');
    }

    public function getPriorityTitle()
    {
        return self::$priorities[$this->getPriority()];
    }

    public function getStatus()
    {
        return $this->getProperty('status');
    }

    public function setStatus($status)
    {
        return $this->setProperty('status', $status);
    }

    public function getStatusTitle()
    {
        $statusTitle = 'Статус неопределен';
        switch ($this->getStatus()) {
            case 'processing' :
                $statusTitle = 'Выполняется';
                break;
            case 'completed' :
                $statusTitle = 'Выполнено';
                break;
            case 'waiting' :
                if ($this->getStartDate() == 0) {
                    $statusTitle = 'Новое';
                } else {
                    $statusTitle = 'Приостановленное';
                }
                break;
        }
        return $statusTitle;
    }

    public function getTitle()
    {
        return $this->getProperty('title');
    }

    public function getCreateDate()
    {
        return $this->getProperty('create_date');
    }

    public function setCreateDate($time)
    {
        return $this->setProperty('create_date', $time);
    }

    public function getCreateDateFormatted()
    {
        return date("d.m.Y H:i", $this->getCreateDate());
    }

    public function getCreatedBy()
    {
        return $this->getProperty('created_by');
    }

    public function setCreatedBy($id)
    {
        return $this->setProperty('created_by', $id);
    }

    public function getCreatedByLogin()
    {
        $user = $this->_userMapper->find($this->getCreatedBy());
        return $user->getLogin();
    }

    public function getAssignedTo()
    {
        return $this->getProperty('assigned_to');
    }

    public function getAssignedToLogin()
    {
        $user = $this->_userMapper->find($this->getAssignedTo());
        return $user->getLogin();
    }

    public function getStartDate()
    {
        return $this->getProperty('start_date');
    }

    public function setStartDate($time)
    {
        return $this->setProperty('start_date', $time);
    }

    public function getStartDateFormatted()
    {
        return $this->getStartDate() > 0
            ? date("d.m.Y H:i", $this->getStartDate())
            : 'Не начато';
    }

    public function getEndDate()
    {
        return $this->getProperty('end_date');
    }

    public function setEndDate($time)
    {
        return $this->setProperty('end_date', $time);
    }

    public function getEndDateFormatted()
    {
        return $this->getEndDate() > 0
            ? date("d.m.Y H:i", $this->getEndDate())
            : 'Не выполнено';
    }

    public function getText()
    {
        return $this->getProperty('text');
    }
}

