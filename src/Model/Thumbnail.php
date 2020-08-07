<?php

namespace Thmsu\YouTubeData\Model;

class Thumbnail
{
    const TYPE_DEFAULT  = 'default';
    const TYPE_MEDIUM   = 'medium';
    const TYPE_HIGH     = 'high';
    const TYPE_STANDARD = 'standard';
    const TYPE_MAX_RES  = 'maxRes';

    protected string $url;

    protected int $width;

    protected int $height;

    public function __construct(object $item)
    {
        $this->url    = $item->url;
        $this->width  = $item->width;
        $this->height = $item->height;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
