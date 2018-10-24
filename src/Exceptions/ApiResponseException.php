<?php

namespace Ipunkt\LaravelIndexer\Client\Exceptions;

use Rokde\HttpClient\Response;

class ApiResponseException extends \RuntimeException
{
    /**
     * @param \Rokde\HttpClient\Response $response
     * @return ApiResponseException
     */
    public static function fromErrorResponse(Response $response): ApiResponseException
    {
        $data = $response->json();
        $errors = array_get($data, 'errors', []);
        $error = current($errors);
        return new static(array_get($error, 'title'), array_get($error, 'code'));
    }
}