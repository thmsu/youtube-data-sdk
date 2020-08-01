<?php

namespace Thmsu\YouTubeData\Response;

use Thmsu\YouTubeData\Mapper\MapChannel;
use Thmsu\YouTubeData\Model\Channel;

class ChannelListResponse extends AbstractResponse
{
    use MapChannel;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @return \Thmsu\YouTubeData\Model\Channel|object
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
