# laravel-indexer-client

[![Latest Stable Version](https://poser.pugx.org/ipunkt/laravel-indexer-client/v/stable.svg)](https://packagist.org/packages/ipunkt/laravel-indexer-client) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/laravel-indexer-client/v/unstable.svg)](https://packagist.org/packages/ipunkt/laravel-indexer-client) [![License](https://poser.pugx.org/ipunkt/laravel-indexer-client/license.svg)](https://packagist.org/packages/ipunkt/laravel-indexer-client) [![Total Downloads](https://poser.pugx.org/ipunkt/laravel-indexer-client/downloads.svg)](https://packagist.org/packages/ipunkt/laravel-indexer-client)

A php client for the [laravel-indexer-service](https://github.com/ipunkt/laravel-indexer-service).

## Installation

```bash
composer require ipunkt/laravel-indexer-client
```

If you need a < php7 release use the 1.* version.

### Binding in Laravel

In any ServiceProvider do the following:

```php
public function register()
{
    $this->app->bind(IndexerClient::class, function () {
        $host = config('indexer.service');
        $token = config('indexer.token');
        try {
            $httpClient = new Client();
            $client = new IndexerClient($host, $httpClient, $token);
            return $client;
        } catch (Exception $exception) {
            throw new \RuntimeException('IndexerClient can not be instantiated.', 0, $exception);
        }
    });
}
```
