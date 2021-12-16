<?php

namespace Thmsu\YouTubeData\Model;

class ChannelImage
{
    public const BANNER_EXTERNAL_URL               = 'bannerExternalUrl';
    public const BANNER_IMAGE_URL                  = 'bannerImageUrl';
    public const BANNER_TABLET_LOW_IMAGE_URL       = 'bannerTabletLowImageUrl';
    public const BANNER_TABLET_IMAGE_URL           = 'bannerTabletImageUrl';
    public const BANNER_TABLET_HD_IMAGE_URL        = 'bannerTabletHdImageUrl';
    public const BANNER_TABLET_EXTRA_HD_IMAGE_URL  = 'bannerTabletExtraHdImageUrl';
    public const BANNER_MOBILE_IMAGE_URL           = 'bannerMobileImageUrl';
    public const BANNER_MOBILE_LOW_IMAGE_URL       = 'bannerMobileLowImageUrl';
    public const BANNER_MOBILE_MEDIUM_HD_IMAGE_URL = 'bannerMobileMediumHdImageUrl';
    public const BANNER_MOBILE_HD_IMAGE_URL        = 'bannerMobileHdImageUrl';
    public const BANNER_MOBILE_EXTRA_HD_IMAGE_URL  = 'bannerMobileExtraHdImageUrl';
    public const BANNER_TV_IMAGE_URL               = 'bannerTvImageUrl';
    public const BANNER_TV_LOW_IMAGE_URL           = 'bannerTvLowImageUrl';
    public const BANNER_TV_MEDIUM_IMAGE_URL        = 'bannerTvMediumImageUrl';
    public const BANNER_TV_HIGH_IMAGE_URL          = 'bannerTvHighImageUrl';

    protected string $url;

    protected string $type;

    public function __construct(string $type, string $url)
    {
        $this->type = $type;
        $this->url  = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
