<?php

namespace Thmsu\YouTubeData\Response;

use Psr\Http\Message\ResponseInterface;
use Thmsu\YouTubeData\Model\Video;

class VideoListResponse extends AbstractResponse
{
    protected Video $video;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->video    = new Video($this->getContent()->items[0]);
    }

    public function getVideo(): Video
    {
        return $this->video;
    }
}
