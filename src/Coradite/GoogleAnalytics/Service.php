<?php

/*
 * This file is part of the Wid'op package.
 *
 * (c) Wid'op <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Coradite\GoogleAnalytics;

/**
 * Google Analytics service.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Service
{
    /** @var Client */
    protected $client;

    /**
     * Google analytics service constructor.
     *
     * @param Client $client The google analytics client.
     */
    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * Gets the google analytics client.
     *
     * @return Client The google analytics client.
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the google analytics client.
     *
     * @param Client $client The google analytics client.
     *
     * @return Service The google analytics service.
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Queries the google analytics service.
     *
     * @param Command $command The command with the query params
     *
     * @return Response If an error occurred when querying the google analytics service.
     *
     * @throws GoogleAnalyticsException If Query is invalid
     *
     */
    public function query(Command $command)
    {
        $accessToken = $this->getClient()->getAccessToken();
        $uri = $command->build($accessToken);
        $content = $this->getClient()->getHttpAdapter()->getContent($uri);
        $json = json_decode($content, true);

        if (!is_array($json) || isset($json['error'])) {
            throw GoogleAnalyticsException::invalidQuery(isset($json['error']) ? $json['error']['message'] : 'Invalid json');
        }

        return new Response($json, $command);
    }
}
