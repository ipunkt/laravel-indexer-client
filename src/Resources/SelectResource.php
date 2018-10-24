<?php

namespace Ipunkt\LaravelIndexer\Client\Resources;

use Ipunkt\LaravelIndexer\Client\Entities\QueryResult;
use Ipunkt\LaravelIndexer\Client\Entities\SelectQuery;
use Ipunkt\LaravelIndexer\Client\Exceptions\ApiResponseException;

class SelectResource extends Resource
{
    /**
     * returns a query result
     *
     * @param SelectQuery $query
     * @return QueryResult
     * @throws ApiResponseException
     */
    public function get(SelectQuery $query)
    {
        $response = $this->_index($query->toArray());

        if ($response->status() === 200) {
            return QueryResult::fromResponse($response);
        }

        throw ApiResponseException::fromErrorResponse($response);
    }

    /**
     * returns full url for resource
     *
     * @param string $baseUrl
     * @return string
     */
    protected function url($baseUrl): string
    {
        return $baseUrl . 'secure/v1/select';
    }
}