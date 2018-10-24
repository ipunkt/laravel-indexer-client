<?php

namespace Ipunkt\LaravelIndexer\Client;

use Ipunkt\LaravelIndexer\Client\Resources\IndexResource;
use Ipunkt\LaravelIndexer\Client\Resources\SelectResource;
use Rokde\HttpClient\Client;

class IndexerClient
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var \Rokde\HttpClient\Client
     */
    private $client;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var IndexResource
     */
    private $indexResource = null;

    /**
     * @var SelectResource
     */
    private $selectResource = null;

    /**
     * IndexerClient constructor.
     * @param string $host
     * @param \Rokde\HttpClient\Client $client
     * @param string $token
     */
    public function __construct($host, Client $client, $token)
    {
        $this->client = $client;

        $this->host = rtrim($host, '/') . '/';

        $this->setSecretToken($token);
    }

    /**
     * set secret token
     *
     * @param string $token
     * @return $this
     */
    private function setSecretToken($token)
    {
        if (isset($this->headers['Authorization'])) {
            unset($this->headers['Authorization']);
        }

        $this->addHeader('Authorization', 'Token ' . $token);
        return $this;
    }

    /**
     * adds a header for each resource request
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * returns index resource
     *
     * @return IndexResource
     */
    public function index()
    {
        if ($this->indexResource === null) {
            $this->indexResource = new IndexResource($this->client(), $this->host, $this->headers);
        }

        return $this->indexResource;
    }

    /**
     * returns select resource
     *
     * @return SelectResource
     */
    public function select()
    {
        if ($this->selectResource === null) {
            $this->selectResource = new SelectResource($this->client(), $this->host, $this->headers);
        }

        return $this->selectResource;
    }

    /**
     * returns client
     *
     * @return \Rokde\HttpClient\Client
     */
    protected function client()
    {
        return $this->client;
    }
}