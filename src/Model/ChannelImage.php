<?php

namespace Thmsu\YouTubeData\Model;

class ChannelImage
{
    const BANNER_IMAGE_URL                  = 'bannerImageUrl';
    const BANNER_TABLET_LOW_IMAGE_URL       = 'bannerTabletLowImageUrl';
    const BANNER_TABLET_IMAGE_URL           = 'bannerTabletImageUrl';
    const BANNER_TABLET_HD_IMAGE_URL        = 'bannerTabletHdImageUrl';
    const BANNER_TABLET_EXTRA_HD_IMAGE_URL  = 'bannerTabletExtraHdImageUrl';
    const BANNER_MOBILE_IMAGE_URL           = 'bannerMobileImageUrl';
    const BANNER_MOBILE_LOW_IMAGE_URL       = 'bannerMobileLowImageUrl';
    const BANNER_MOBILE_MEDIUM_HD_IMAGE_URL = 'bannerMobileMediumHdImageUrl';
    const BANNER_MOBILE_HD_IMAGE_URL        = 'bannerMobileHdImageUrl';
    const BANNER_MOBILE_EXTRA_HD_IMAGE_URL  = 'bannerMobileExtraHdImageUrl';
    const BANNER_TV_IMAGE_URL               = 'bannerTvImageUrl';
    const BANNER_TV_LOW_IMAGE_URL           = 'bannerTvLowImageUrl';
    const BANNER_TV_MEDIUM_IMAGE_URL        = 'bannerTvMediumImageUrl';
    const BANNER_TV_HIGH_IMAGE_URL          = 'bannerTvHighImageUrl';

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
