<?php

namespace Thmsu\YouTubeData\Model;

class VideoStatistics
{
    protected int $viewCount = 0;

    protected int $likeCount = 0;

    protected int $dislikeCount = 0;

    protected int $favoriteCount = 0;

    protected int $commentCount = 0;

    public function __construct(object $item)
    {
        $this->viewCount     = $item->viewCount;
        $this->likeCount     = $item->likeCount;
        $this->dislikeCount  = $item->dislikeCount;
        $this->favoriteCount = $item->favoriteCount;
        $this->commentCount  = $item->commentCount;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function getDislikeCount(): int
    {
        return $this->dislikeCount;
    }

    public function getFavoriteCount(): int
    {
        return $this->favoriteCount;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }
}
