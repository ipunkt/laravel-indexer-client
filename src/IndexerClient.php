<?php

namespace Ipunkt\LaravelIndexer\Client;

use Guzzle\Http\ClientInterface;
use Ipunkt\LaravelIndexer\Client\Resources\IndexResource;

class IndexerClient
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var ClientInterface
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
     * IndexerClient constructor.
     * @param string $host
     * @param ClientInterface $client
     */
    public function __construct($host, ClientInterface $client)
    {
        $this->client = $client;
        $this->client->setBaseUrl($host);

        $this->host = rtrim($host, '/') . '/';
    }

    /**
     * set secret token
     *
     * @param string $token
     * @return $this
     */
    public function setSecretToken($token)
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
     * returns client
     *
     * @return ClientInterface
     */
    protected function client()
    {
        return $this->client;
    }
}