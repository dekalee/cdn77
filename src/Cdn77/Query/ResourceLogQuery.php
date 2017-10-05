<?php

namespace Dekalee\Cdn77\Query;

/**
 * Class ResourceLogQuery
 */
class ResourceLogQuery extends AbstractPurgeQuery implements QueryInterface
{
    const URL = 'https://api.cdn77.com/v2.0/log/details';

    /**
     * @param string|null $resource
     *
     * @return array
     */
    public function execute($resource = null)
    {
        $this->populateCDNResources();

        foreach ($this->cdnResources as $cdnResource) {
            if (false !== strpos($cdnResource['cname'], $resource)) {
                $url = sprintf($this->url . '?login=%s&passwd=%s&cdn_id=%d&period=4&http_status[]=cached&http_status[]=noncached',
                    $this->login,
                    $this->password,
                    $cdnResource['id']
                );

                $response = $this->client->get($url);

                $responseData = json_decode($response->getBody()->getContents(), true);

                return $responseData['logs'];
            }
        }

        return [];
    }

}
