<?php

namespace Thmsu\YouTubeData\Response;

use Psr\Http\Message\ResponseInterface;

class AbstractResponse
{
    protected ResponseInterface $response;

    protected ?object $content = null;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    protected function getContent(): object
    {
        if (!$this->content) {
            $this->content = json_decode($this->response->getBody()->getContents());
        }

        return $this->content;
    }
}
