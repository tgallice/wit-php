<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Client;
use Tgallice\Wit\Model\Context;

class MessageApiSpec extends ObjectBehavior
{
    function let(Client $client, ResponseInterface $response)
    {
        $response->getBody()->willReturn('{"field": "value"}');
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\MessageApi');
    }

    function it_should_extract_meaning_of_sentence($client, $response)
    {
        $context = new Context();
        $query = [
            'q' => 'my text',
            'context' => json_encode($context),
            'foo' => 'bar',
        ];

        $client->get('/message', $query)->willReturn($response);

        $this->extractMeaning('my text', $context, ['foo' => 'bar'])->shouldReturn(['field' => 'value']);
    }

    function it_should_extract_meaning_of_sentence_without_override_query_message($client, $response)
    {
        $context = new Context();
        $query = [
            'q' => 'my text',
            'context' => json_encode($context),
        ];

        $client->get('/message', $query)->willReturn($response);

        $this->extractMeaning('my text', $context, ['q' => 'other message'])->shouldReturn(['field' => 'value']);
    }
}
