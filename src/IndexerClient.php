<?php

namespace Ipunkt\LaravelIndexer\Client;

use Ipunkt\LaravelIndexer\Client\Resources\IndexResource;
use Ipunkt\LaravelIndexer\Client\Resources\SelectResource;
use Rokde\HttpClient\Client;

class IndexerClient
{
    /** @var string */
    private $host;

    /** @var \Rokde\HttpClient\Client */
    private $client;

    /** @var array */
    private $headers = [];

    /** @var IndexResource */
    private $indexResource = null;

    /** @var SelectResource */
    private $selectResource = null;

    /** @var callable|null */
    private $requestPreparation;

    /** @var int */
    private $maxtries = 1;

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
     * define a preparation request callback
     *
     * Signature: callback(\Rokde\HttpClient\Request $request): void
     *
     * @param callable|null $callback
     * @return \Ipunkt\LaravelIndexer\Client\IndexerClient
     */
    public function prepareRequest($callback = null): self
    {
        $this->requestPreparation = $callback;

        return $this;
    }

    public function setMaxTries($tries): self
    {
        $this->maxtries = max($tries + 0, 1);

        return $this;
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

        return $this->indexResource->prepareRequest($this->requestPreparation)
            ->setMaxTries($this->maxtries);
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

        return $this->selectResource->prepareRequest($this->requestPreparation)
            ->setMaxTries($this->maxtries);
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