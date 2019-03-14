<?php

namespace Ipunkt\LaravelIndexer\Client\Resources;

use Rokde\HttpClient\Client;
use Rokde\HttpClient\Request;
use Rokde\HttpClient\Response;

abstract class Resource
{
    /** @var \Rokde\HttpClient\Client */
    private $client;

    /** @var string */
    private $baseUrl;

    /** @var array */
    private $headers;

    /**
     * Resource constructor.
     * @param \Rokde\HttpClient\Client $client
     * @param string $baseUrl
     * @param array $headers
     */
    public function __construct(Client $client, $baseUrl, array $headers = [])
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->headers = $headers;
    }

    /**
     * returns full url for resource
     *
     * @param string $baseUrl
     * @return string
     */
    abstract protected function url($baseUrl): string;

    /**
     * returns an resource index via get
     *
     * @param array $queryParams
     * @param array $headers
     * @return \Rokde\HttpClient\Response
     */
    protected function _index(array $queryParams = [], array $headers = []): Response
    {
        $queryString = http_build_query($queryParams);
        if ($queryString !== '') {
            $queryString = '?' . $queryString;
        }

        $request = new Request(
            $this->url($this->baseUrl) . $queryString,
            'GET',
            $this->prepareHeaders($headers)
        );

        return $this->client->send($request);
    }

    /**
     * posts a message
     *
     * @param array $data
     * @param array $headers
     * @return \Rokde\HttpClient\Response
     */
    protected function _post($data, array $headers = []): Response
    {
        $request = new Request(
            $this->url($this->baseUrl),
            'POST',
            $this->prepareHeaders($headers)
        );
        $request->setBody($this->prepareBody($data));

        return $this->client->send($request);
    }

    /**
     * deletes a resource
     *
     * @param string|integer $id
     * @param array $headers
     * @return \Rokde\HttpClient\Response
     */
    protected function _delete($id, array $headers = []): Response
    {
        $request = new Request(
            $this->url($this->baseUrl) . '/' . $id,
            'DELETE',
            $this->prepareHeaders($headers)
        );

        return $this->client->send($request);
    }

    /**
     * deletes a resource by query
     *
     * @param string $query
     * @param array $headers
     * @return \Rokde\HttpClient\Response
     */
    protected function _deleteWithQuery($query, array $headers = []): Response
    {
        $request = new Request(
            $this->url($this->baseUrl) . '/-',
            'DELETE',
            $this->prepareHeaders($headers)
        );
        $request->setBody($this->prepareBody(['query' => $query]));

        return $this->client->send($request);
    }

    /**
     * create request model data
     *
     * @param string $type
     * @param array $data
     * @param string|int|null $id
     * @return array
     */
    protected function createRequestModel($type, array $data = [], $id = null): array
    {
        return [
            'data' => [
                'id' => $id,
                'type' => $type,
                'attributes' => $data,
            ],
        ];
    }

    /**
     * prepares body
     *
     * @param mixed $data
     * @return null|string
     */
    private function prepareBody($data)
    {
        if ($data === null) {
            return null;
        }
        return json_encode($data);
    }

    /**
     * prepares headers
     *
     * @param array $headers
     * @return array
     */
    private function prepareHeaders(array $headers)
    {
        $headers['Accept'] = 'application/vnd.api+json';
        $headers['Content-Type'] = 'application/vnd.api+json';
        return array_merge($this->headers, $headers);
    }
}