<?php

namespace Dekalee\Cdn77\Query;

/**
 * Class PurgeAllQuery
 */
class PurgeAllQuery extends AbstractResourcesAwareQuery implements QueryInterface
{
    const URL = 'https://api.cdn77.com/v2.0/data/purge';

    /**
     * @param null  $resource
     *
     * @return mixed|null
     */
    public function execute($resource = null)
    {
        $this->populateCDNResources();

        $cdnResources = array_filter($this->cdnResources, function($value, $key) use ($resource) {
            return $resource === null || $value['cname'] == $resource;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($cdnResources as $cdnResource) {
            $this->client->post($this->url, [
                'form_params' => [
                    'cdn_id' => $cdnResource['id'],
                    'login' => $this->login,
                    'passwd' => $this->password,
                ]
            ]);
        }

        return;
    }
}
