<?php

namespace Merx\YouTubeData\Response;

use Merx\YouTubeData\Mapper\MapVideo;
use Merx\YouTubeData\Model\Video;

class VideoListResponse extends AbstractResponse
{
    use MapVideo;

    /**
     * @var Video
     */
    protected $video;

    /**
     * @return \Merx\YouTubeData\Model\Video|object
     * @throws \JsonMapper_Exception
     */
    public function getVideo()
    {
        if (!$this->video) {
            $content = $this->getContent();
            $this->video = $this->mapVideo($content->items[0]);
        }

        return $this->video;
    }
}