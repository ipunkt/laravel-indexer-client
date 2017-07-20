<?php

namespace Ipunkt\LaravelIndexer\Client\Entities;

use Guzzle\Http\Message\Response;

class QueryResult
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @var float
     */
    private $maxScore = 0.0;

    /**
     * QueryResult constructor.
     * @param array $data
     * @param array $meta
     */
    public function __construct(array $data, array $meta = array())
    {
        $this->data = $data;

        $this->pagination = new Pagination(
            +array_get($meta, 'pagination.start', 0),
            +array_get($meta, 'pagination.rows', 0),
            +array_get($meta, 'pagination.total', 0),
            +array_get($meta, 'pagination.page', 1)
        );

        $this->maxScore = (float)array_get($meta, 'result.max-score', 0.0);
    }

    /**
     * data array, result documents
     *
     * @return array
     */
    public function documents()
    {
        return $this->data;
    }

    /**
     * returns pagination
     *
     * @return Pagination
     */
    public function pagination()
    {
        return $this->pagination;
    }

    /**
     * returns max score
     *
     * @return float
     */
    public function maxScore()
    {
        return $this->maxScore;
    }

    /**
     * creates result from response
     *
     * @param Response $response
     * @return static
     * @throws \Guzzle\Common\Exception\RuntimeException
     */
    public static function fromResponse(Response $response)
    {
        $data = $response->json();

        return new static(
            array_get($data, 'data', array()),
            array_get($data, 'meta', array())
        );
    }
}