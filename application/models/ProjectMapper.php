<?php

class Application_Model_ProjectMapper extends Creogen_DataMapper
{
    /**
     * @var string
     */
    protected $_domainObject = 'Application_Model_Project';

    /**
     * @var string
     */
    protected $_dbTableClass = 'Application_Model_DbTable_Project';

    public function countManagedByUser($id)
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('count' => 'COUNT(*)'))
            ->where('manager_id = ?', $id);

        $row = $this->getDbTable()->fetchRow($select);

        return $row['count'];
    }
}