<?php

class Application_Model_CommentMapper extends Creogen_DataMapper
{
    /**
     * @var string
     */
    protected $_domainObject = 'Application_Model_Comment';

    /**
     * @var string
     */
    protected $_dbTableClass = 'Application_Model_DbTable_Comment';

    public function findByTask($taskId)
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'))
            ->where('task_id = ?', $taskId)
            ->order('date');

        $rows = $this->getDbTable()->fetchAll($select);

        if (!$rows) {
            return array();
        }

        $ret = array();
        foreach ($rows as $row) {
            $ret[] = $this->find($row['id']);
        }
        return $ret;
    }
}

