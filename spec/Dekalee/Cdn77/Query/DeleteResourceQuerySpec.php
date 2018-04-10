<?php

namespace spec\Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;
use Dekalee\Cdn77\Query\DeleteResourceQuery;
use Dekalee\Cdn77\Query\ListResourcesQuery;
use Dekalee\Cdn77\Query\QueryInterface;
use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class DeleteResourceQuerySpec extends ObjectBehavior
{
    function let(ListResourcesQuery $listResourcesQuery, Client $client)
    {
        $listResourcesQuery->execute()->willReturn([['id' => 1, 'cname' => 'foo.bar.co'], ['id' => 2, 'cname' => 'foo.bar.co'], ['id' => 3, 'cname' => 'example.test.com']]);
        $this->beConstructedWith($listResourcesQuery, 'login', 'password', 'url', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteResourceQuery::CLASS);
    }

    function it_should_be_a_query()
    {
        $this->shouldHaveType(QueryInterface::CLASS);
    }

    function it_should_delete_one_resource(
        Client $client,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $client->post('url?id=3login=login&passwd=password')->shouldBeCalled()->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode([
            'status' => 'ok',
            'description' => "Request was successful.",
        ]));

        $this->execute('example.test.com');
    }

    function it_should_throw_exception_when_deleting_multiple_resources(Client $client)
    {
        $client->post(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(QueryErrorException::CLASS)->during('execute', ['foo.bar.co']);
    }

    function it_should_throw_exception_when_no_resources_given(Client $client)
    {
        $client->post(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(QueryErrorException::CLASS)->during('execute');
    }
}
