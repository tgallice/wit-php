<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Client;
use Tgallice\Wit\Model\Context;
use Tgallice\Wit\ResponseHandler;

class ConverseApiSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\ConverseApi');
    }

    function it_should_converse($client, ResponseInterface $response)
    {
        $body = '
            {
                "type": "merge",
                "entities": {
                    "location": [
                        {
                            "body": "Brussels",
                            "value": {
                                "type": "value",
                                "value": "Brussels",
                                "suggested": true
                            },
                            "start": 11,
                            "end": 19,
                            "entity": "location"
                        }
                    ]
                },
                "confidence": 1
            }
        ';

        $context = new Context();
        $query = [
            'session_id' => 'session_id',
            'q' => 'my message',
        ];

        $response->getBody()->willReturn($body);
        $client->send('POST', '/converse', $context, $query)->willReturn($response);

        $this->converse('session_id', 'my message', $context)->shouldReturn(json_decode($body, true));
    }
}
