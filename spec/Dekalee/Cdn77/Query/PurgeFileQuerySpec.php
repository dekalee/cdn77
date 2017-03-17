<?php

namespace spec\Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Query\ListResourcesQuery;
use Dekalee\Cdn77\Query\PurgeFileQuery;
use Dekalee\Cdn77\Query\QueryInterface;
use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PurgeFileQuerySpec extends ObjectBehavior
{
    function let(Client $client, ListResourcesQuery $listResourcesQuery)
    {
        $listResourcesQuery->execute()->willReturn([['id' => 1, 'cname' => 'hosted.adback.co'], ['id' => 2, 'cname' => 'hosted.adback.co'], ['id' => 3, 'cname' => 'example.test.com']]);
        $client->post(Argument::any(), Argument::any())->willReturn(null);
        $this->beConstructedWith($listResourcesQuery, 'login', 'password', 'url', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PurgeFileQuery::CLASS);
    }

    function it_should_be_a_query()
    {
        $this->shouldHaveType(QueryInterface::CLASS);
    }

    public function it_should_purge_all_given_elements(Client $client)
    {
        $client->post('url', [
            'form_params' => [
                'cdn_id' => 1,
                'login' => 'login',
                'passwd' => 'password',
                'url' => [
                    '/file1',
                    '/file+2',
                ],
            ],
        ])->shouldBeCalled();
        $client->post('url', [
            'form_params' => [
                'cdn_id' => 2,
                'login' => 'login',
                'passwd' => 'password',
                'url' => [
                    '/file1',
                    '/file+2',
                ],
            ],
        ])->shouldBeCalled();
        $client->post('url', [
            'form_params' => [
                'cdn_id' => 3,
                'login' => 'login',
                'passwd' => 'password',
                'url' => [
                    '/file1',
                    '/file+2',
                ],
            ],
        ])->shouldNotBeCalled();

        $this->execute('hosted.adback.co', ['file1', 'file 2']);
    }
}
