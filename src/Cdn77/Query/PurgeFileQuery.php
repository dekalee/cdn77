<?php

namespace Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;
use GuzzleHttp\Client;

/**
 * Class PurgeFileQuery
 */
class PurgeFileQuery extends AbstractPurgeQuery implements QueryInterface
{
    const URL = 'https://api.cdn77.com/v2.0/data/purge';

    /**
     * @param null  $resource
     * @param array $files
     *
     * @return mixed|null
     */
    public function execute($resource = null, array $files = array())
    {
        $this->populateCDNResources();

        $urls = [];
        foreach ($files as $file) {
            $urls[] = '/' . urlencode($file);
        }

        $cdnResources = array_filter($this->cdnResources, function($value, $key) use ($resource) {
            return $value['cname'] == $resource;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($cdnResources as $cdnResource) {
            $this->client->post($this->url, [
                'form_params' => [
                    'cdn_id' => $cdnResource['id'],
                    'login' => $this->login,
                    'passwd' => $this->password,
                    'url' => $urls
                ]
            ]);
        }

        return;
    }
}
