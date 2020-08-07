<?php

namespace Thmsu\YouTubeData\Model;

use DateTimeImmutable;
use stdClass;

class Video
{
    protected string $videoId;

    protected string $title;

    protected string $description;

    protected ?string $categoryId;

    protected string $liveBroadcastContent;

    protected ?string $defaultLanguage;

    protected ?string $defaultAudioLanguage;

    protected DateTimeImmutable $publishedAt;

    protected string $channelId;

    protected string $channelTitle;

    protected array $tags = [];

    /** @var Thumbnail[] */
    protected array $thumbnails = [];

    protected ?VideoStatistics $statistics;

    protected ?VideoContentDetails $contentDetails;

    public function __construct(object $item)
    {
        $this->videoId              = $item->id->videoId ?? $item->id;
        $this->title                = $item->snippet->title;
        $this->description          = $item->snippet->description;
        $this->categoryId           = $item->categoryId ?? $item->snippet->categoryId ?? null;
        $this->liveBroadcastContent = $item->snippet->liveBroadcastContent;
        $this->defaultLanguage      = $item->snippet->defaultLanguage      ?? null;
        $this->defaultAudioLanguage = $item->snippet->defaultAudioLanguage ?? null;
        $this->tags                 = $item->snippet->tags                 ?? [];
        $this->publishedAt          = new DateTimeImmutable($item->snippet->publishedAt);
        $this->channelId            = $item->snippet->channelId;
        $this->channelTitle         = $item->snippet->channelTitle;
        $this->statistics           = property_exists($item, 'statistics')
            ? new VideoStatistics($item->statistics)
            : null;
        $this->contentDetails = property_exists($item, 'contentDetails')
            ? new VideoContentDetails($item->contentDetails)
            : null;

        $this->thumbnails = array_reduce(array_keys(get_object_vars($item->snippet->thumbnails ?? new stdClass()) ?? []), function (array $list, string $key) use ($item) {
            $list[$key] = new Thumbnail($item->snippet->thumbnails->$key);

            return $list;
        }, []);
    }

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function getUrl(): string
    {
        return 'https://www.youtube.com/watch?v='.$this->getVideoId();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getLiveBroadcastContent(): string
    {
        return $this->liveBroadcastContent;
    }

    public function getDefaultLanguage(): ?string
    {
        return $this->defaultLanguage;
    }

    public function getDefaultAudioLanguage(): ?string
    {
        return $this->defaultAudioLanguage;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public function getChannelTitle(): string
    {
        return $this->channelTitle;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    /** @return Thumbnail[] */
    public function getThumbnails(): array
    {
        return $this->thumbnails;
    }

    public function getThumbnail(string $type): ?Thumbnail
    {
        return $this->thumbnails[$type] ?? null;
    }

    public function getStatistics(): ?VideoStatistics
    {
        return $this->statistics;
    }

    public function getDetails(): ?VideoContentDetails
    {
        return $this->contentDetails;
    }
}
