<?php

namespace Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;
use GuzzleHttp\Client;

/**
 * Class ListResourcesQuery
 */
class ListResourcesQuery implements QueryInterface
{
    protected $url;
    protected $login;
    protected $client;
    protected $password;

    const URL = 'https://api.cdn77.com/v2.0/cdn-resource/list';

    /**
     * @param string      $login
     * @param string      $password
     * @param string      $url
     * @param Client|null $client
     */
    public function __construct($login, $password, $url = self::URL, Client $client = null)
    {
        $this->url = $url;
        $this->login = $login;
        $this->client = $client?: new Client();
        $this->password = $password;
    }

    /**
     * @return mixed|null
     * @throws QueryErrorException
     */
    public function execute()
    {
        $response = $this->client->get(
            sprintf(
                '%s?login=%s&passwd=%s',
                $this->url,
                $this->login,
                $this->password
            )
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if ('ok' != $data['status']) {
            throw new QueryErrorException($data['description']);
        }

        return $data['cdnResources'];
    }
}
