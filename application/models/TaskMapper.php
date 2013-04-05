<?php

class Application_Model_TaskMapper extends Creogen_DataMapper
{
    /**
     * @var string
     */
    protected $_domainObject = 'Application_Model_Task';

    /**
     * @var string
     */
    protected $_dbTableClass = 'Application_Model_DbTable_Task';

    public function findAll()
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'))
            ->order('id DESC');

        $rows = $this->getDbTable()->fetchAll($select);

        $objects = array();

        foreach ($rows as $row) {
            $objects[] = $this->find($row->id);
        }

        return $objects;
    }

    public function findActualTasksForUser($id)
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'))
            ->where('assigned_to = ?', $id)
            ->where('status != ?', 'completed')
            ->order('id DESC');

        $rows = $this->getDbTable()->fetchAll($select);

        $objects = array();

        foreach ($rows as $row) {
            $objects[] = $this->find($row->id);
        }

        return $objects;
    }

    public function filterByProjectFilterAndAssignedTo($projectId, $filter, $assignedTo)
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'))
            ->order('id DESC');

        if ($projectId) {
            $select->where('project_id = ?', $projectId);
        }

        if ($filter) {
            switch ($filter) {
                case 'new' :
                    $select->where('status = ?', 'waiting');
                    $select->where('start_date = ?', 0);
                    break;
                case 'processing' :
                    $select->where('status = ?', 'processing');
                    break;
                case 'waiting' :
                    $select->where('status = ?', 'waiting');
                    $select->where('start_date > ?', 0);
                    break;
                case 'completed' :
                    $select->where('status = ?', 'completed');
                    break;
            }
        }

        if ($assignedTo) {
            $select->where('assigned_to = ?', $assignedTo);
        }

        $rows = $this->getDbTable()->fetchAll($select);

        $objects = array();

        foreach ($rows as $row) {
            $objects[] = $this->find($row->id);
        }

        return $objects;
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function countTotalForProject($id)
    {
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
                ->from($this->getDbTable(), array('count' => 'COUNT(*)'))
                ->where('project_id = ?', $id)
        );

        if (!$row) {
            return 0;
        }

        return $row['count'];
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function countNotCompleteForProject($id)
    {
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
                ->from($this->getDbTable(), array('count' => 'COUNT(*)'))
                ->where('project_id = ?', $id)
                ->where('status != ?', 'completed')
        );

        if (!$row) {
            return 0;
        }

        return $row['count'];
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function countTotalForUser($id)
    {
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
                ->from($this->getDbTable(), array('count' => 'COUNT(*)'))
                ->where('assigned_to = ?', $id)
        );

        if (!$row) {
            return 0;
        }

        return $row['count'];
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function countNotCompleteForUser($id)
    {
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
                ->from($this->getDbTable(), array('count' => 'COUNT(*)'))
                ->where('assigned_to = ?', $id)
                ->where('status != ?', 'completed')
        );

        if (!$row) {
            return 0;
        }

        return $row['count'];
    }

    public function dropByProject($id)
    {
        return $this->getDbTable()->delete(array('project_id = ?', $id));
    }
}