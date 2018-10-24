<?php

namespace Ipunkt\LaravelIndexer\Client\Resources;

use Ipunkt\LaravelIndexer\Client\Exceptions\ApiResponseException;

class IndexResource extends Resource
{
    /**
     * store any data on index, optional id for forcing id
     *
     * @param array $data
     * @param null $id
     * @return bool
     * @throws ApiResponseException
     */
    public function store(array $data, $id = null)
    {
        $response = $this->_post($this->createRequestModel('items', $data, $id));

        if ($response->status() === 204) {
            return true;
        }

        throw ApiResponseException::fromErrorResponse($response);
    }

    /**
     * delete an document by id from index
     *
     * @param int|string $id
     * @return bool
     * @throws ApiResponseException
     */
    public function delete($id)
    {
        $response = $this->_delete($id);

        if ($response->status() === 204) {
            return true;
        }

        throw ApiResponseException::fromErrorResponse($response);
    }

    /**
     * delete an document by id from index
     *
     * @param string $query
     * @return bool
     * @throws ApiResponseException
     */
    public function deleteByQuery($query)
    {
        $response = $this->_deleteWithQuery($query);

        if ($response->status() === 204) {
            return true;
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
        return $baseUrl . 'secure/v1/items';
    }
}