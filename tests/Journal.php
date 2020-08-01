<?php

namespace Merx\YouTubeData\Tests;

use Http\Client\Common\Plugin\Journal as JournalInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Journal implements JournalInterface
{
    private RequestInterface $lastRequest;

    public function getLastRequest(): RequestInterface
    {
        return $this->lastRequest;
    }

    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        $this->lastRequest = $request;
    }

    public function addFailure(RequestInterface $request, ClientExceptionInterface $exception)
    {
        $this->lastRequest = $request;
    }
}
