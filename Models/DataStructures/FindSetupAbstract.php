<?php
namespace Models\DataStructures;


use Models\Condition;

abstract class FindSetupAbstract {

    /**
     * @var array names of fields (or keys) to find
     */
    protected $fields;

    /**
     * Where to get info from
     * @var array
     */
    protected $from;

    /**
     * @var array Array of Condition classes and sub arrays
     */
    protected $where;

    /**
     * @var int how many documents pass from the start
     */
    protected $offset;

    /**
     * @var int how many documents should be fetched
     */
    protected $limit;

    /**
     * Array of OrderBy classes, each representing structure like 'id DESC'
     * @var OrderBy[]
     */
    protected $orderBy;

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param array $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return array
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param array $where
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }

    /**
     * Adding a condition that will be parsed as AND clause
     * @param Condition
     */
    public function andToWhere(Condition $condition)
    {
        $where = $this->getWhere();
        $where[] = $condition;
        $this->setWhere($where);
    }
    /**
     * Adding a condition that will be parsed as OR clause
     * @param Condition[]
     */
    public function orToWhere(array $condition)
    {
        $where = $this->getWhere();
        $where[] = $condition;
        $this->setWhere($where);
    }
    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return OrderBy[]
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param OrderBy[] $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }
}