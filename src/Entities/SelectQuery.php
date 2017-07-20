<?php

namespace Ipunkt\LaravelIndexer\Client\Entities;

class SelectQuery
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var int
     */
    private $start = 0;

    /**
     * @var int
     */
    private $rows = 0;

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @var array
     */
    private $sort = array();

    /**
     * sets query "*:*" for example (field:value)
     *
     * @param string $query
     * @return $this
     */
    public function query($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * sets start
     *
     * @param int $start
     * @return $this
     */
    public function start($start)
    {
        $this->start = +$start;

        return $this;
    }

    /**
     * sets rows
     *
     * @param int $rows
     * @return $this
     */
    public function rows($rows)
    {
        $this->rows = +$rows;

        return $this;
    }

    /**
     * adds a field
     *
     * @param string $field
     * @return $this
     */
    public function field($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * sets fields
     *
     * @param array|string[] $fields
     * @return $this
     */
    public function fields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * sort definition
     *
     * @param string $field
     * @param string $direction (asc|desc)
     * @return $this
     */
    public function sort($field, $direction = 'asc')
    {
        $this->sort[$field] = $direction;

        return $this;
    }

    /**
     * array representation
     *
     * @return array
     */
    public function toArray()
    {
        $result = array();
        if ($this->query !== null) {
            $result['query'] = $this->query;
        }
        if ($this->start !== null) {
            $result['start'] = $this->start;
        }
        if ($this->rows !== null) {
            $result['rows'] = $this->rows;
        }
        if (count($this->fields)) {
            $result['fields'] = $this->fields;
        }
        if (count($this->sort)) {
            $result['sort'] = $this->sort;
        }

        return $result;
    }
}