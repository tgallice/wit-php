<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Client;
use Tgallice\Wit\Model\Context;

class ApiSpec extends ObjectBehavior
{
    function let(Client $client, ResponseInterface $response)
    {
        $response->getBody()->willReturn('{"field": "value"}');
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Api');
    }

    function it_should_get_message_by_id($client, $response)
    {
        $client->get('/messages/id')->willReturn($response);
        $this->getMessage('id')->shouldReturn(['field' => 'value']);
    }
}
