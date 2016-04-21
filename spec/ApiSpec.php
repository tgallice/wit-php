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

    function it_should_get_converse_next_step_with_the_api($client, $response)
    {
        $context = new Context();
        $query = [
            'session_id' => 'session_id',
            'q' => 'my message',
        ];

        $client->send('POST', '/converse', $context, $query)->willReturn($response);

        $this->getConverseNextStep('session_id', 'my message', $context)->shouldReturn(['field' => 'value']);
    }

    function it_should_get_intent_by_text($client, $response)
    {
        $context = new Context();
        $query = [
            'q' => 'my text',
            'context' => json_encode($context),
            'foo' => 'bar',
        ];

        $client->get('/message', $query)->willReturn($response);

        $this->getIntentByText('my text', $context, ['foo' => 'bar'])->shouldReturn(['field' => 'value']);
    }

    function it_should_get_intent_by_text_without_override_query_message($client, $response)
    {
        $context = new Context();
        $query = [
            'q' => 'my text',
            'context' => json_encode($context),
        ];

        $client->get('/message', $query)->willReturn($response);

        $this->getIntentByText('my text', $context, ['q' => 'other message'])->shouldReturn(['field' => 'value']);
    }

    function it_should_get_intent_by_speech_when_its_a_resource_given($client, $response)
    {
        $resource = fopen('php://temp', 'r+');
        $context = new Context();
        $query = ['context' => json_encode($context), 'foo' => 'bar'];

        $client->send('POST', '/speech', $resource, $query)->willReturn($response);

        $this->getIntentBySpeech($resource, $context, ['foo' => 'bar'])->shouldReturn(['field' => 'value']);

        fclose($resource);
    }

    function it_should_get_intent_by_speech_when_its_filename_given($client, $response)
    {
        $context = new Context();
        $query = ['context' => json_encode($context), 'foo' => 'bar'];
        $file = tempnam(sys_get_temp_dir(), 'test');

        $client->send('POST', '/speech', Argument::that(function ($argument) {
            $isResource = is_resource($argument);

            fclose($argument);

            return $isResource;
        }), $query)->willReturn($response);

        $this->getIntentBySpeech($file, $context, ['foo' => 'bar'])->shouldReturn(['field' => 'value']);
    }

    function it_should_throw_exception_when_get_intent_by_speech_with_an_invalid_file_argument()
    {
        $this->shouldThrow(new \InvalidArgumentException('$file argument must be a readable file path or a valid resource'))
            ->duringGetIntentBySpeech('wrong_file.txt');
    }

    function it_should_get_message_by_id($client, $response)
    {
        $client->get('/messages/id')->willReturn($response);
        $this->getMessage('id')->shouldReturn(['field' => 'value']);
    }
}
