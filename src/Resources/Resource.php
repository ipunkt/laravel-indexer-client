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

    /** @var callable|null */
    private $requestPreparation;

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
     * define a preparation request callback
     *
     * Signature: callback(\Rokde\HttpClient\Request $request): void
     *
     * @param callable|null $callback
     * @return \Ipunkt\LaravelIndexer\Client\Resources\Resource
     */
    public function prepareRequest($callback): self
    {
        $this->requestPreparation = $callback;

        return $this;
    }

    /**
     * returns full url for resource
     *
     * @param string $baseUrl
     * @return string
     */
    abstract protected function url($baseUrl): string;

    protected function makeRequest($url, $method, array $headers = []): Request
    {
        $request = new Request($url, $method, $this->prepareHeaders($headers));

        $callback = $this->requestPreparation;
        if ($callback !== null) {
            $callback($request);
        }

        return $request;
    }

    protected function sendRequest(Request $request): Response
    {
        $response = $this->client->send($request);

        return $response;
    }

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

        $request = $this->makeRequest($this->url($this->baseUrl) . $queryString, 'GET', $headers);

        return $this->sendRequest($request);
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
        $request = $this->makeRequest($this->url($this->baseUrl), 'POST', $headers);
        $request->setBody($this->prepareBody($data));

        return $this->sendRequest($request);
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
        $request = $this->makeRequest($this->url($this->baseUrl) . '/' . $id, 'DELETE', $headers);

        return $this->sendRequest($request);
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
        $request = $this->makeRequest($this->url($this->baseUrl) . '/-', 'DELETE', $headers);
        $request->setBody($this->prepareBody(['query' => $query]));

        return $this->sendRequest($request);
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