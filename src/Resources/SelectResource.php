<?php

namespace Ipunkt\LaravelIndexer\Client\Resources;

use Ipunkt\LaravelIndexer\Client\Entities\SelectQuery;
use Ipunkt\LaravelIndexer\Client\Exceptions\ApiResponseException;

class SelectResource extends Resource
{
    /**
     * returns a query result
     *
     * @param SelectQuery $query
     * @return array|bool|float|int|string
     * @throws \Guzzle\Common\Exception\RuntimeException
     * @throws \Guzzle\Http\Exception\RequestException
     * @throws ApiResponseException
     */
    public function get(SelectQuery $query)
    {
        $response = $this->_index($query->toArray());

        if ($response->getStatusCode() === 200) {
            return $response->json();
        }

        throw ApiResponseException::fromErrorResponse($response);
    }

    /**
     * returns full url for resource
     *
     * @param string $baseUrl
     * @return string
     */
    protected function url($baseUrl)
    {
        return $baseUrl . 'secure/v1/select';
    }
}