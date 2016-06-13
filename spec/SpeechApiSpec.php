<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Client;
use Tgallice\Wit\Model\Context;

class SpeechApiSpec extends ObjectBehavior
{
    function let(Client $client, ResponseInterface $response)
    {
        $response->getBody()->willReturn('{"field" : "value"}');
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\SpeechApi');
    }

    function it_should_extract_meaning_of_speech($client, ResponseInterface $response)
    {
        $resource = fopen('php://temp', 'r+');
        $context = new Context();
        $query = ['context' => json_encode($context), 'foo' => 'bar'];

        $client->send('POST', '/speech', $resource, $query)->willReturn($response);

        $this->extractMeaning($resource, $context, ['foo' => 'bar'])->shouldReturn(['field' => 'value']);

        fclose($resource);
    }

    function it_should_extract_meaning_of_speech_when_its_filename_given($client, $response)
    {
        $context = new Context();
        $query = ['context' => json_encode($context), 'foo' => 'bar'];
        $file = tempnam(sys_get_temp_dir(), 'test');

        $client->send('POST', '/speech', Argument::that(function ($argument) {
            $isResource = is_resource($argument);

            fclose($argument);

            return $isResource;
        }), $query)->willReturn($response);

        $this->extractMeaning($file, $context, ['foo' => 'bar'])->shouldReturn(['field' => 'value']);
    }

    function it_should_throw_exception_when_extract_meaning_of_speech_with_an_invalid_file_argument()
    {
        $this->shouldThrow(new \InvalidArgumentException('$file argument must be a readable file path or a valid resource'))
            ->duringExtractMeaning('wrong_file.txt');
    }
}
