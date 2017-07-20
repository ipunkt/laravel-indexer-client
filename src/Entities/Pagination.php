<?php

namespace Ipunkt\LaravelIndexer\Client\Entities;

class Pagination
{
    private $start;
    private $rows;
    private $total;
    private $page;

    /**
     * Pagination constructor.
     * @param integer $start
     * @param integer $rows
     * @param integer $total
     * @param integer $page
     */
    public function __construct($start, $rows, $total, $page)
    {
        $this->start = $start;
        $this->rows = $rows;
        $this->total = $total;
        $this->page = $page;
    }

    /**
     * returns Start
     *
     * @return integer
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * returns Rows
     *
     * @return integer
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * returns Total
     *
     * @return integer
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * returns Page
     *
     * @return integer
     */
    public function page()
    {
        return $this->page;
    }
}