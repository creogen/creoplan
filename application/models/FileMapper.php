<?php

class Application_Model_FileMapper extends Creogen_DataMapper
{
    /**
     * @var string
     */
    protected $_domainObject = 'Application_Model_File';

    /**
     * @var string
     */
    protected $_dbTableClass = 'Application_Model_DbTable_File';

    public function findByTask($id)
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'))
            ->where('task_id = ?', $id);

        $rows = $this->getDbTable()->fetchAll($select);

        if (!$rows) {
            return false;
        }

        $files = array();
        foreach ($rows as $row) {
            $files[] = $this->find($row['id']);
        }

        return $files;
    }
}