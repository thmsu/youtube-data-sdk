<?php

namespace Merx\YouTubeData\Response;

use Merx\YouTubeData\Mapper\MapChannel;
use Merx\YouTubeData\Model\Channel;

class ChannelListResponse extends AbstractResponse
{
    use MapChannel;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @return \Merx\YouTubeData\Model\Channel|object
     * @throws \JsonMapper_Exception
     */
    public function getChannel()
    {
        if (!$this->channel) {
            $content = $this->getContent();
            $this->channel = $this->mapChannel($content->items[0]);
        }

        return $this->channel;
    }
}
