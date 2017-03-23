<?php

namespace spec\Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;
use Dekalee\Cdn77\Query\CreateResourceQuery;
use Dekalee\Cdn77\Query\QueryInterface;
use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CreateResourceQuerySpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith('login', 'password', 'url', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateResourceQuery::CLASS);
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
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode([
            "status" => "ok",
            "description" => "Request was successful.",
            "cdnResource" => [
                "id" => "123",
                "origin_scheme" => "http",
                "origin_url" => "hosted.adback.co",
                "cname" => "foo.bar",
                "cdn_url" => "123.rsc.cdn77.org",
                "cache_expiry" => 17280,
                "url_signing_on" => 0,
                "qs_status" => 0,
                "setcookie_status" => 0,
                "other_cnames" => [],
                "label" => "yourLabel",
                "storage_id" => null,
            ]
        ]));
        $client->post('url', [
            'form_params' => [
                'login' => 'login',
                'passwd' => 'password',
                'label' => 'foo.bar',
                'type' => 'standard',
                'origin_scheme' => 'http',
                'origin_url' => 'hosted.adback.co',
                'cname' => 'foo.bar',
            ]
        ])->shouldBeCalled()->willReturn($response);

        $this->execute('foo.bar')->shouldBeEqualTo('123.rsc.cdn77.org');
    }

    function it_should_throw_exception_if_request_not_good(
        Client $client,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode([
            "status" => "nok",
            "description" => "Request was not successful.",
            "cdnResource" => []
        ]));
        $client->post('url', [
            'form_params' => [
                'login' => 'login',
                'passwd' => 'password',
                'label' => 'foo.bar',
                'type' => 'standard',
                'origin_scheme' => 'http',
                'origin_url' => 'hosted.adback.co',
                'cname' => 'foo.bar',
            ]
        ])->shouldBeCalled()->willReturn($response);

        $this->shouldThrow(QueryErrorException::CLASS)->duringExecute('foo.bar');
    }
}
