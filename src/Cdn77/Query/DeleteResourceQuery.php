<?php

namespace Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;

/**
 * Class DeleteResourceQuery
 */
class DeleteResourceQuery extends AbstractResourcesAwareQuery implements QueryInterface
{
    const URL = 'https://api.cdn77.com/v2.0/cdn-resource/delete';

    /**
     * @param null $resource
     *
     * @return mixed|null
     * @throws QueryErrorException
     */
    public function execute($resource = null)
    {
        if (null === $resource) {
            throw new QueryErrorException('Could not delete no resource');
        }

        $this->populateCDNResources();

        $cdnResources = array_filter($this->cdnResources, function($value, $key) use ($resource) {
            return $value['cname'] == $resource;
        }, ARRAY_FILTER_USE_BOTH);

        if (count($cdnResources) > 1) {
            throw new QueryErrorException('Could not delete more than one resource');
        }

        foreach ($cdnResources as $cdnResource) {
            $response = $this->client->post($this->url, [
                'form_params' => [
                    'id' => $cdnResource['id'],
                    'login' => $this->login,
                    'passwd' => $this->password,
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            if ('ok' !== $data['status']) {
                throw new QueryErrorException(json_encode($data));
            }
        }

        return;
    }
}
