<?php

namespace Dekalee\Cdn77\Query;

use GuzzleHttp\Client;

/**
 * Class AbstractPurgeQuery
 */
abstract class AbstractPurgeQuery
{
    protected $url;
    protected $login;
    protected $client;
    protected $password;
    protected $listResourcesQuery;

    const URL = '';

    protected $cdnResources;

    /**
     * @param ListResourcesQuery $listResourcesQuery
     * @param string      $login
     * @param string      $password
     * @param string      $url
     * @param Client|null $client
     */
    public function __construct(ListResourcesQuery $listResourcesQuery, $login, $password, $url = null, Client $client = null)
    {
        $this->url = null === $url?static::URL: $url;
        $this->login = $login;
        $this->client = $client?: new Client();
        $this->password = $password;
        $this->listResourcesQuery = $listResourcesQuery;
    }

    /**
     * Populate CDN resources
     */
    protected function populateCDNResources()
    {
        if (null === $this->cdnResources) {
            $this->cdnResources = $this->listResourcesQuery->execute();
        }
    }
}
