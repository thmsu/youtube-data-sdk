<?php

namespace Thmsu\YouTubeData\Response;

use Thmsu\YouTubeData\Model\Video;

class SearchResponse extends AbstractResponse
{
    /** @var Video[] */
    protected ?array $videos = null;

    /** @return Video[] */
    public function getVideos(): array
    {
        if (!$this->videos) {
            $content = $this->getContent();
            dump($content);

            $this->videos = array_map(function (object $item) {
                return new Video($item);
            }, $content->items);
        }

        return $this->videos;
    }

    public function getPrevPageToken(): ?string
    {
        $content = $this->getContent();

        return property_exists($content, 'prevPageToken') ? $content->prevPageToken : null;
    }

    public function getNextPageToken(): ?string
    {
        $content = $this->getContent();

        return property_exists($content, 'nextPageToken') ? $content->nextPageToken : null;
    }

    public function getTotalResults(): ?int
    {
        $content = $this->getContent();

        return property_exists($content, 'pageInfo') ? $content->pageInfo->totalResults : null;
    }
}
