<?php

class Creogen_DataMapper
{
    /**
     * @var string
     */
    protected $_domainObject = null;

    /**
     * @var string
     */
    protected $_dbTableClass = null;

    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable = null;

    /**
     * @var array of Creogen_DataMapper
     */
    protected static $_instances = array();

    /**
     * @var Zend_Cache
     */
    protected static $_cache = null;

    /**
     * @throws Exception
     *
     * @param Zend_Db_Table_Abstract|string $dbTable
     *
     * @return DataMapper
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }

        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }

        $this->_dbTable = $dbTable;

        return $this;
    }

    /**
     * @return string Template of cache key for current class
     */
    public function getCacheKey()
    {
        return sprintf('creoplan_ru_%s_%%d', $this->_domainObject);
    }

    /**
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable($this->_dbTableClass);
        }

        return $this->_dbTable;
    }

    /**
     * @param int $id
     *
     * @return Creogen_DomainObject
     */
    public function find($id, $useCache = true)
    {
        $cacheKey = sprintf($this->getCacheKey(), $id);

        if ($useCache && self::$_cache && self::$_cache->test($cacheKey)) {
            $properties = self::$_cache->load($cacheKey);
        } else {
            $data = $this->getDbTable()->find($id);

            if (0 == count($data)) {
                return false;
            }

            $row = $data->current();
            $properties = $row->toArray();

            if ($useCache && self::$_cache) {
                self::$_cache->save($properties, $cacheKey);
            }

        }

        /**
         * @var Creogen_DomainObject $Object
         */
        $Object = new $this->_domainObject;

        $Object->setPropertyArray($properties, true);

        return $Object;
    }

    /**
     * @param int $id
     *
     * @return Creogen_DomainObject
     */
    public function findOrCreate($id)
    {
        $Object = $this->find($id);

        if ($Object) {
            return $Object;
        }

        return new $this->_domainObject;
    }

    public function findBy($fieldName, $value)
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'))
            ->where(sprintf('%s = ?', $fieldName), $value)
            ->limit(1);

        $row = $this->getDbTable()->fetchRow($select);

        if (!$row) {
            return false;
        }

        $Object = $this->find($row['id']);

        return $Object;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $select = $this->getDbTable()->select()
            ->from($this->getDbTable(), array('id'));


        $resultSet = $this->getDbTable()->fetchAll($select);

        $objects = array();

        foreach ($resultSet as $row) {
            /**
             * @var Creogen_DomainObject $Object
             */

            $objects[] = $this->find($row->id);
        }

        return $objects;
    }

    /**
     * @param Creogen_DomainObject $Object
     *
     * @return void
     */
    public function save(Creogen_DomainObject $Object)
    {
        if (null === ($id = $Object->getId())) {
            $data = $Object->getPropertyArray();
            unset($data['id']);
            $id = $this->getDbTable()->insert($data);
            $Object->setId($id);
        } else {
            $data = $Object->getModifiedProperties();
            if (count($data)) {
                $this->getDbTable()->update($data, array('id = ?' => $id));
            }
        }

        if (self::$_cache) {
            $cacheKey = sprintf($this->getCacheKey(), $Object->getId());
            self::$_cache->remove($cacheKey);
        }
    }

    /**
     * @param Creogen_DomainObject $Object
     *
     * @return bool|int
     */
    public function drop(Creogen_DomainObject $Object)
    {
        if (!$id = $Object->getId()) {
            return false;
        }

        if (self::$_cache) {
            $cacheKey = sprintf($this->getCacheKey(), $id);
            self::$_cache->remove($cacheKey);
        }

        return $this->getDbTable()->delete(array('id = ?' => $id));
    }

    /**
     * @return Creogen_DataMapper
     */
    public static function getInstance($className)
    {
        if (empty(self::$_instances[$className])) {
            self::$_instances[$className] = new $className();
        }

        return self::$_instances[$className];
    }

    public static function setCache($cache)
    {
        self::$_cache = $cache;
    }
}