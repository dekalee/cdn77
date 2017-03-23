<?php

namespace Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;
use GuzzleHttp\Client;

/**
 * Class CreateResourceQuery
 */
class CreateResourceQuery implements QueryInterface
{
    protected $url;
    protected $login;
    protected $client;
    protected $password;

    const URL = 'https://api.cdn77.com/v2.0/cdn-resource/create';

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
     * @param string|null $domain
     *
     * @return mixed|null
     * @throws QueryErrorException
     */
    public function execute($domain = null)
    {
        $response = $this->client->post(
            $this->url,
            [
                'form_params' => [
                    'login' => $this->login,
                    'passwd' => $this->password,
                    'label' => $domain,
                    'type' => 'standard',
                    'origin_scheme' => 'http',
                    'origin_url' => 'hosted.adback.co',
                    'cname' => $domain,
                ]
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if ('ok' != $data['status']) {
            throw new QueryErrorException($data['description']);
        }

        return $data['cdnResource']['cdn_url'];
    }
}
