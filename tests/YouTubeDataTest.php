<?php

namespace Thmsu\YouTubeData\Tests;

use function GuzzleHttp\Psr7\parse_query;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use Http\Discovery\HttpClientDiscovery;
use PHPUnit\Framework\TestCase;
use Thmsu\YouTubeData\Model\Channel;
use Thmsu\YouTubeData\Model\ChannelImage;
use Thmsu\YouTubeData\Model\Thumbnail;
use Thmsu\YouTubeData\Model\Video;
use Thmsu\YouTubeData\Model\VideoContentDetails;
use Thmsu\YouTubeData\Model\VideoStatistics;
use Thmsu\YouTubeData\Response\ChannelListResponse;
use Thmsu\YouTubeData\Response\SearchResponse;
use Thmsu\YouTubeData\Response\VideoListResponse;
use Thmsu\YouTubeData\YouTubeData;

class YouTubeDataTest extends TestCase
{
    private Journal $journal;

    /** @test */
    public function it_returns_a_valid_video_search_response()
    {
        $client = $this->getClient();

        $youtube = YouTubeData::create($_SERVER['YOUTUBE_API_KEY'], $client);

        $result = $youtube->search('surfing', 5);

        //# Request
        $request = $this->journal->getLastRequest();
        $query   = parse_query($request->getUri()->getQuery());
        $this->assertEquals($_SERVER['YOUTUBE_API_KEY'], $query['key']);
        $this->assertEquals('surfing', $query['q']);
        $this->assertEquals(5, $query['maxResults']);

        //# Response
        $this->assertInstanceOf(SearchResponse::class, $result);
        $this->assertEquals(null, $result->getPrevPageToken());
        $this->assertEquals('CAUQAA', $result->getNextPageToken());
        $this->assertEquals(1000000, $result->getTotalResults());

        //# Video Mapping
        $this->assertCount(5, $result->getVideos());
        $video = $result->getVideos()[0];

        $this->assertEquals('W7h-Yho8EB0', $video->getVideoId());
        $this->assertEquals('GoPro: Top 10 Surf Moments', $video->getTitle());
        $this->assertEquals('Celebrate International Surf Day with GoPro\'s Top 10 Surf Moments. Shot 100% on GoPro: http://bit.ly/2wUMwfI Get stoked and subscribe: http://goo.gl/HgVXpQ ...', $video->getDescription());
        $this->assertEquals('https://www.youtube.com/watch?v=W7h-Yho8EB0', $video->getUrl());
        $this->assertEquals('UCqhnX4jA0A5paNd1v-zEysw', $video->getChannelId());
        $this->assertEquals('GoPro', $video->getChannelTitle());
        $this->assertEquals('2019-06-15', $video->getPublishedAt()->format('Y-m-d'));
        $this->assertEquals('none', $video->getLiveBroadcastContent());

        //# Thumbnails
        $this->assertCount(3, $video->getThumbnails());
        $thumbnail = $video->getThumbnail(Thumbnail::TYPE_DEFAULT);

        $this->assertEquals('https://i.ytimg.com/vi/W7h-Yho8EB0/default.jpg', $thumbnail->getUrl());
        $this->assertEquals(120, $thumbnail->getWidth());
        $this->assertEquals(90, $thumbnail->getHeight());
    }

    /** @test */
    public function it_returns_a_valid_video_list_response()
    {
        $youtube = YouTubeData::create($_SERVER['YOUTUBE_API_KEY'], $this->getClient());

        $result = $youtube->getVideoById('Ks-_Mh1QhMc');

        //# Request
        $request = $this->journal->getLastRequest();
        $query   = parse_query($request->getUri()->getQuery());
        $this->assertEquals('Ks-_Mh1QhMc', $query['id']);
        $this->assertEquals('snippet,contentDetails,statistics', $query['part']);
        $this->assertEquals($_SERVER['YOUTUBE_API_KEY'], $query['key']);

        //# Response
        $this->assertInstanceOf(VideoListResponse::class, $result);

        //# Video Mapping
        $video = $result->getVideo();
        $this->assertInstanceOf(Video::class, $video);

        $this->assertEquals('Ks-_Mh1QhMc', $video->getVideoId());
        $this->assertEquals('Your body language may shape who you are | Amy Cuddy', $video->getTitle());
        $this->assertStringStartsWith('Body language affects how others see us', $video->getDescription());
        $this->assertEquals('https://www.youtube.com/watch?v=Ks-_Mh1QhMc', $video->getUrl());
        $this->assertEquals('UCAuUUnT6oDeKwE6v1NGQxug', $video->getChannelId());
        $this->assertEquals('TED', $video->getChannelTitle());
        $this->assertEquals('2012-10-01', $video->getPublishedAt()->format('Y-m-d'));
        $this->assertEquals('none', $video->getLiveBroadcastContent());
        $this->assertEquals('22', $video->getCategoryId());
        $this->assertTrue(in_array('Amy Cuddy', $video->getTags()));
        $this->assertTrue(in_array('TED', $video->getTags()));
        $this->assertTrue(in_array('psychology', $video->getTags()));
        $this->assertEquals('en', $video->getDefaultLanguage());
        $this->assertEquals('en', $video->getDefaultAudioLanguage());

        //# Thumbnails
        $this->assertCount(5, $video->getThumbnails());

        //# Statistics
        $stats = $video->getStatistics();
        $this->assertInstanceOf(VideoStatistics::class, $stats);
        $this->assertEquals(18351979, $stats->getViewCount());
        $this->assertEquals(263492, $stats->getLikeCount());
        $this->assertEquals(5173, $stats->getDislikeCount());
        $this->assertEquals(0, $stats->getFavoriteCount());
        $this->assertEquals(8221, $stats->getCommentCount());

        //# Content Details
        $details = $video->getDetails();
        $this->assertInstanceOf(VideoContentDetails::class, $details);
        $this->assertEquals('21', $details->getDuration()->format('%i'));
        $this->assertEquals('hd', $details->getDefinition());
        $this->assertEquals('2d', $details->getDimension());
        $this->assertEquals('rectangular', $details->getProjection());
        $this->assertEquals('true', $details->getCaption());
        $this->assertEquals(true, $details->isLicensedContent());
    }

    /** @test */
    public function it_returns_a_valid_channel_list_response()
    {
        $youtube = YouTubeData::create($_SERVER['YOUTUBE_API_KEY'], $this->getClient());

        $result = $youtube->getChannelById('UC_x5XG1OV2P6uZZ5FSM9Ttw', ['snippet', 'contentDetails', 'statistics', 'brandingSettings']);

        //# Request
        $request = $this->journal->getLastRequest();
        $query   = parse_query($request->getUri()->getQuery());
        $this->assertEquals('UC_x5XG1OV2P6uZZ5FSM9Ttw', $query['id']);
        $this->assertEquals('snippet,contentDetails,statistics,brandingSettings', $query['part']);
        $this->assertEquals($_SERVER['YOUTUBE_API_KEY'], $query['key']);

        //# Response
        $this->assertInstanceOf(ChannelListResponse::class, $result);

        //# Channel Mapping
        $channel = $result->getChannel();
        $this->assertInstanceOf(Channel::class, $channel);

        $this->assertEquals('UC_x5XG1OV2P6uZZ5FSM9Ttw', $channel->getId());
        $this->assertEquals('Google Developers', $channel->getTitle());
        $this->assertStringStartsWith('The Google Developers channel features talks from events', $channel->getDescription());
        $this->assertEquals('https://www.youtube.com/channel/UC_x5XG1OV2P6uZZ5FSM9Ttw', $channel->getUrl());
        $this->assertEquals('googlecode', $channel->getCustomUrl());
        $this->assertEquals('2007-08-23', $channel->getPublishedAt()->format('Y-m-d'));
        $this->assertCount(3, $channel->getThumbnails());
        $this->assertInstanceOf(Thumbnail::class, $channel->getThumbnail(Thumbnail::TYPE_DEFAULT));

        //# Brand Settings
        $brand = $channel->getBrandingSettings();

        $this->assertEquals('#000000', $brand->getProfileColor());
        $this->assertCount(14, $brand->getImages());
        $this->assertEquals(ChannelImage::BANNER_IMAGE_URL, $brand->getImageByType(ChannelImage::BANNER_IMAGE_URL)->getType());
    }

    protected function getClient(): PluginClient
    {
        $namingStrategy = new PathNamingStrategy();

        $recorder = new FilesystemRecorder(__DIR__.'/.responses');

        $record = new RecordPlugin($namingStrategy, $recorder);

        $replay = new ReplayPlugin($namingStrategy, $recorder, false);

        $this->journal = new Journal();

        $historyPlugin = new HistoryPlugin($this->journal);

        $pluginClient = new PluginClient(
            HttpClientDiscovery::find(),
            [$historyPlugin, $replay, $record]
        );

        return $pluginClient;
    }
}
