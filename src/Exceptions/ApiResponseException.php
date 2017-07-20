<?php

namespace Ipunkt\LaravelIndexer\Client\Exceptions;

use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\Message\Response;

class ApiResponseException extends RuntimeException
{
    public static function fromErrorResponse(Response $response)
    {
        $data = $response->json();
        $errors = array_get($data, 'errors', array());
        $error = current($errors);
        return new static(array_get($error, 'title'), array_get($error, 'code'));
    }
}