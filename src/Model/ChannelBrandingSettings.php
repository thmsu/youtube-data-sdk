<?php

namespace Thmsu\YouTubeData\Model;

use stdClass;

class ChannelBrandingSettings
{
    protected ?string $profileColor = null;

    /** @var ChannelImage[] */
    protected array $images;

    public function __construct(object $item)
    {
        $this->profileColor = $item->channel->profileColor ?? null;
        $this->images       = array_reduce(array_keys(get_object_vars($item->image ?? new stdClass()) ?? []), function (array $list, string $key) use ($item) {
            $list[$key] = new ChannelImage($key, $item->image->$key);

            return $list;
        }, []);
    }

    public function getProfileColor(): ?string
    {
        return $this->profileColor;
    }

    /**
     * @return ChannelImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function getImageByType(string $type): ?ChannelImage
    {
        foreach ($this->images as $image) {
            if ($image->getType() === $type) {
                return $image;
            }
        }

        return null;
    }
}
