<?php

namespace spec\Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;
use Dekalee\Cdn77\Query\ListResourcesQuery;
use Dekalee\Cdn77\Query\QueryInterface;
use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ListResourcesQuerySpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith('login', 'password', 'url', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ListResourcesQuery::CLASS);
    }

    function it_should_be_a_query()
    {
        $this->shouldHaveType(QueryInterface::CLASS);
    }

    function it_should_return_all_cdn_ids(
        Client $client,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $data = [
            [
                'id' => 84928,
                'ssl' => "no",
                'origin_scheme' => "http",
                'origin_url' => "hosted.adback.co",
                'cname' => "waltersburgburgoon.adback.co",
                'cdn_url' => "1231266247.rsc.cdn77.org",
                'cache_expiry' => 17280,
                'url_signing_on' => 0,
                'url_signing_key' => "",
                'qs_status' => 0,
                'setcookie_status' => 0,
                'other_cnames' => [ ],
                'platform' => "nxg",
                'label' => "AdBack Burgburgoon",
                'https_redirect_code' => null,
                'ignored_query_params' => [ ],
                'hlp_type' => null,
                'hlp_deny_empty_referer' => 0,
                'hlp_referer_domains' => [ ],
                'storage_id' => null,
                'mp4_pseudo_on' => 0,
                'http2' => 1
            ],
            [
                'id' => 84929,
                'ssl' => "no",
                'origin_scheme' => "http",
                'origin_url' => "hosted.adback.co",
                'cname' => "waltersburgburgoon.adback.co",
                'cdn_url' => "1231266247.rsc.cdn77.org",
                'cache_expiry' => 17280,
                'url_signing_on' => 0,
                'url_signing_key' => "",
                'qs_status' => 0,
                'setcookie_status' => 0,
                'other_cnames' => [ ],
                'platform' => "nxg",
                'label' => "AdBack Burgburgoon",
                'https_redirect_code' => null,
                'ignored_query_params' => [ ],
                'hlp_type' => null,
                'hlp_deny_empty_referer' => 0,
                'hlp_referer_domains' => [ ],
                'storage_id' => null,
                'mp4_pseudo_on' => 0,
                'http2' => 1
            ],
        ];

        $client->get('url?login=login&passwd=password')->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode([
            'status' => 'ok',
            'description' => "Request was successful.",
            'cdnResources' => $data,
        ]));

        $this->execute()->shouldBeEqualTo($data);
    }

    function it_should_throw_exception_on_bad_call(
        Client $client,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $client->get('url?login=login&passwd=password')->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode([
            'status' => 'error',
            'description' => "There was an error",
        ]));

        $this->shouldThrow(QueryErrorException::CLASS)->duringExecute();
    }
}
