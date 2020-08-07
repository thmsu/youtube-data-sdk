<?php

namespace Thmsu\YouTubeData\Model;

use DateTimeImmutable;
use stdClass;

class Channel
{
    protected string $id;

    protected string $title;

    protected string $description;

    protected string $customUrl;

    protected DateTimeImmutable $publishedAt;

    /** @var Thumbnail[] */
    protected array $thumbnails;

    protected ?ChannelBrandingSettings $brandingSettings;

    public function __construct(object $item)
    {
        $this->id               = $item->id;
        $this->title            = $item->snippet->title;
        $this->description      = $item->snippet->description;
        $this->customUrl        = $item->snippet->customUrl;
        $this->publishedAt      = new DateTimeImmutable($item->snippet->publishedAt);
        $this->brandingSettings = new ChannelBrandingSettings($item->brandingSettings);

        $this->thumbnails = array_reduce(array_keys(get_object_vars($item->snippet->thumbnails ?? new stdClass()) ?? []), function (array $list, string $key) use ($item) {
            $list[$key] = new Thumbnail($item->snippet->thumbnails->$key);

            return $list;
        }, []);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return 'https://www.youtube.com/channel/'.$this->id;
    }

    public function getCustomUrl(): string
    {
        return $this->customUrl;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @return Thumbnail[]
     */
    public function getThumbnails(): array
    {
        return $this->thumbnails;
    }

    public function getThumbnail(string $type): ?Thumbnail
    {
        return $this->thumbnails[$type] ?? null;
    }

    public function getBrandingSettings(): ?ChannelBrandingSettings
    {
        return $this->brandingSettings;
    }

    public function setBrandingSettings(ChannelBrandingSettings $brandingSettings): self
    {
        $this->brandingSettings = $brandingSettings;

        return $this;
    }
}
