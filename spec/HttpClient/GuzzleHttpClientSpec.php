<?php

namespace spec\Tgallice\Wit\HttpClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClientSpec extends ObjectBehavior
{
    function let(ClientInterface $guzzleClient)
    {
        $this->beConstructedWith($guzzleClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\HttpClient\GuzzleHttpClient');
    }

    function it_send_request_and_return_a_psr_response($guzzleClient, ResponseInterface $response)
    {
        $options = [
            RequestOptions::QUERY => ['q' => 'test'],
            RequestOptions::HEADERS => ['header' => 'value'],
            RequestOptions::BODY => 'body',
            'timeout' => 10,
        ];

        $guzzleClient->request('POST', '/uri', $options)->willReturn($response);

        $this->send('POST', '/uri', 'body', ['q' => 'test'], ['header' => 'value'], ['timeout' => 10])->shouldReturn($response);
    }

    function it_send_request_with_json_content($guzzleClient, ResponseInterface $response)
    {
        $options = [
            RequestOptions::QUERY => [],
            RequestOptions::HEADERS => [],
            RequestOptions::JSON => ['body'],
        ];

        $guzzleClient->request('POST', '/uri', $options)->willReturn($response);

        $this->send('POST', '/uri', ['body'])->shouldReturn($response);
    }
}
