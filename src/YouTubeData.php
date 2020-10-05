<?php

namespace Thmsu\YouTubeData;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Thmsu\YouTubeData\Response\ChannelListResponse;
use Thmsu\YouTubeData\Response\SearchResponse;
use Thmsu\YouTubeData\Response\VideoListResponse;

class YouTubeData
{
    const BASE_URL = 'https://www.googleapis.com/youtube/v3';

    protected HttpClient $client;

    protected RequestFactoryInterface $requestFactory;

    protected ?string $apiKey = null;

    protected function __construct(HttpClient $client = null, RequestFactoryInterface $messageFactory = null)
    {
        $this->client         = $client ?: HttpClientDiscovery::find();
        $this->requestFactory = $messageFactory ?: Psr17FactoryDiscovery::findRequestFactory();
    }

    public static function create(string $apiKey, HttpClient $client = null, RequestFactoryInterface $messageFactory = null): YouTubeData
    {
        $youtube = new self($client, $messageFactory);
        $youtube->setApiKey($apiKey);

        return $youtube;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setClient(HttpClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function setRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    public function search(string $query, int $maxResults = 10, array $config = []): SearchResponse
    {
        $url = self::BASE_URL.'/search';

        $query = array_merge([
            'q'          => $query,
            'maxResults' => $maxResults,
            'part'       => 'snippet',
            'key'        => $this->apiKey,
            'type'       => 'video'
        ], $config);

        $url .= '?'.http_build_query($query);

        $request  = $this->requestFactory->createRequest('GET', $url);
        $response = $this->sendRequest($request);

        return new SearchResponse($response);
    }

    public function moreSearchResults(string $nextPageToken): SearchResponse
    {
        $url = self::BASE_URL.'/search';

        $query = array_merge([
            'pageToken' => $nextPageToken,
//            'maxResults' => $maxResults,
            'part' => 'snippet',
            'key'  => $this->apiKey,
            'type'       => 'video'
        ]);

        $url .= '?'.http_build_query($query);

        $request  = $this->requestFactory->createRequest('GET', $url);
        $response = $this->sendRequest($request);

        return new SearchResponse($response);
    }

    public function getVideoById(string $id, array $parts = ['snippet', 'contentDetails', 'statistics']): VideoListResponse
    {
        $url = self::BASE_URL.'/videos';
        $url .= '?'.http_build_query([
                'id'   => $id,
                'part' => join(',', $parts),
                'key'  => $this->apiKey,
            ]);

        $request  = $this->requestFactory->createRequest('GET', $url);
        $response = $this->sendRequest($request);

        return new VideoListResponse($response);
    }

    public function getChannelById(string $id, array $parts = ['snippet']): ChannelListResponse
    {
        $url = self::BASE_URL.'/channels';
        $url .= '?'.http_build_query([
                'id'   => $id,
                'part' => join(',', $parts),
                'key'  => $this->apiKey,
            ]);

        $request  = $this->requestFactory->createRequest('GET', $url);
        $response = $this->sendRequest($request);

        return new ChannelListResponse($response);
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
