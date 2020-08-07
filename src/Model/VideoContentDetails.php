<?php

namespace Thmsu\YouTubeData\Model;

use DateInterval;

class VideoContentDetails
{
    protected DateInterval $duration;

    protected string $dimension;

    protected string $definition;

    protected string $caption;

    protected bool $licensedContent;

    protected string $projection;

    public function __construct(object $item)
    {
        $this->duration        = new DateInterval($item->duration);
        $this->dimension       = $item->dimension;
        $this->definition      = $item->definition;
        $this->caption         = $item->caption;
        $this->licensedContent = $item->licensedContent;
        $this->projection      = $item->projection;
    }

    public function getDuration(): DateInterval
    {
        return $this->duration;
    }

    public function getDimension(): string
    {
        return $this->dimension;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function isLicensedContent(): bool
    {
        return $this->licensedContent;
    }

    public function getProjection(): string
    {
        return $this->projection;
    }
}
