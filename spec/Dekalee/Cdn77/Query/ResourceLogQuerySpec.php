<?php

namespace spec\Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Query\ListResourcesQuery;
use Dekalee\Cdn77\Query\QueryInterface;
use Dekalee\Cdn77\Query\ResourceLogQuery;
use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResourceLogQuerySpec extends ObjectBehavior
{
    function let(ListResourcesQuery $listResourcesQuery, Client $client)
    {
        $listResourcesQuery->execute()->willReturn([['id' => 1, 'cname' => 'foo.bar.co'], ['id' => 2, 'cname' => 'foo.baz.co'], ['id' => 3, 'cname' => 'example.test.com']]);

        $this->beConstructedWith($listResourcesQuery, 'login', 'password', 'url', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceLogQuery::CLASS);
    }

    function it_should_be_a_query()
    {
        $this->shouldHaveType(QueryInterface::CLASS);
    }

    function it_should_get_log_from_resource(
        Client $client,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $logs = [
            [
                "date" => null,
                "time" => "11:29:37",
                "data_center_location_id" => "Paris",
                "client_ip" => "105.101.219.118",
                "http_status" => "cached",
                "size" => "16817",
                "path" => "/sculpin.js"
            ]
        ];

        $client->get('url?login=login&passwd=password&cdn_id=1&period=4&http_status[]=cached&http_status[]=noncached')->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode([
            "status" => "ok",
            "description" => "Request was successful.",
            "logs" => $logs
        ]));

        $this->execute('bar.co')->shouldBeEqualTo($logs);
    }

    function it_should_get_empty_array_if_resource_does_not_exists(Client $client)
    {
        $client->get(Argument::any())->shouldNotBeCalled();

        $this->execute('foo.co')->shouldBeEqualTo([]);
    }
}
