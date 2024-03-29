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
    public function itReturnsAValidVideoSearchResponse()
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

        $this->assertEquals('ma67yOdMQfs', $video->getVideoId());
        $this->assertEquals('These Were The All-Time Surfing Moments Of The Year | Best Of 2020', $video->getTitle());
        $this->assertEquals('Well, that was a weird ride. Though it hasn\'t been easy, at least when we fixed our gaze on the ocean — or favorite place in the ...', $video->getDescription());
        $this->assertEquals('https://www.youtube.com/watch?v=ma67yOdMQfs', $video->getUrl());
        $this->assertEquals('UC--3c8RqSfAqYBdDjIG3UNA', $video->getChannelId());
        $this->assertEquals('Red Bull Surfing', $video->getChannelTitle());
        $this->assertEquals('2021-01-23', $video->getPublishedAt()->format('Y-m-d'));
        $this->assertEquals('none', $video->getLiveBroadcastContent());

        //# Thumbnails
        $this->assertCount(3, $video->getThumbnails());
        $thumbnail = $video->getThumbnail(Thumbnail::TYPE_DEFAULT);

        $this->assertEquals('https://i.ytimg.com/vi/ma67yOdMQfs/default.jpg', $thumbnail->getUrl());
        $this->assertEquals(120, $thumbnail->getWidth());
        $this->assertEquals(90, $thumbnail->getHeight());
    }

    /** @test */
    public function itReturnsAValidVideoListResponse()
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
        $this->assertTrue(\in_array('Amy Cuddy', $video->getTags()));
        $this->assertTrue(\in_array('TED', $video->getTags()));
        $this->assertTrue(\in_array('psychology', $video->getTags()));
        $this->assertEquals('en', $video->getDefaultLanguage());
        $this->assertEquals('en', $video->getDefaultAudioLanguage());

        //# Thumbnails
        $this->assertCount(5, $video->getThumbnails());

        //# Statistics
        $stats = $video->getStatistics();
        $this->assertInstanceOf(VideoStatistics::class, $stats);
        $this->assertEquals(20571094, $stats->getViewCount());
        $this->assertEquals(317563, $stats->getLikeCount());
        $this->assertEquals(0, $stats->getFavoriteCount());
        $this->assertEquals(8959, $stats->getCommentCount());

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
    public function itReturnsAValidChannelListResponse()
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
        $this->assertEquals('googledevelopers', $channel->getCustomUrl());
        $this->assertEquals('2007-08-23', $channel->getPublishedAt()->format('Y-m-d'));
        $this->assertCount(3, $channel->getThumbnails());
        $this->assertInstanceOf(Thumbnail::class, $channel->getThumbnail(Thumbnail::TYPE_DEFAULT));

        //# Brand Settings
        $brand = $channel->getBrandingSettings();

        $this->assertEquals(null, $brand->getProfileColor());
        $this->assertCount(1, $brand->getImages());
        $this->assertEquals(ChannelImage::BANNER_EXTERNAL_URL, $brand->getImageByType(ChannelImage::BANNER_EXTERNAL_URL)->getType());
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
