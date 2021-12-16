<?php

namespace Thmsu\YouTubeData\Model;

class Thumbnail
{
    public const TYPE_DEFAULT  = 'default';
    public const TYPE_MEDIUM   = 'medium';
    public const TYPE_HIGH     = 'high';
    public const TYPE_STANDARD = 'standard';
    public const TYPE_MAX_RES  = 'maxRes';

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
