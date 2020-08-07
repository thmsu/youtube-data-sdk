<?php

namespace Thmsu\YouTubeData\Response;

use Thmsu\YouTubeData\Model\Channel;

class ChannelListResponse extends AbstractResponse
{
    protected ?Channel $channel = null;

    public function getChannel(): Channel
    {
        if (!$this->channel) {
            $content       = $this->getContent();
            $this->channel = new Channel($content->items[0]);
        }

        return $this->channel;
    }
}
