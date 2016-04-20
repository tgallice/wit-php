<?php

namespace spec\Tgallice\Wit;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Exception\BadResponseException;

class ClientSpec extends ObjectBehavior
{
    function let(ClientInterface $httpClient, ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(200);
        $this->beConstructedWith('token', $httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Client');
    }

    function it_has_shortcut_post_request($httpClient, $response)
    {
        $httpClient->request('POST', '/uri', Argument::withEntry('json', ['obj' => 'value']))->willReturn($response);
        $this->post('/uri', ['obj' => 'value'])->shouldReturn($response);
    }

    function it_has_shortcut_get_request($httpClient, $response)
    {
        $httpClient->request('GET', '/uri', Argument::withEntry('query', ['q' => 'text']))->willReturn($response);
        $this->get('/uri', ['q' => 'text'])->shouldReturn($response);
    }

    function it_has_shortcut_put_request($httpClient, $response)
    {
        $httpClient->request('PUT', '/uri', Argument::withEntry('json', ['obj' => 'value']))->willReturn($response);
        $this->put('/uri', ['obj' => 'value'])->shouldReturn($response);
    }

    function it_has_shortcut_delete_request($httpClient, $response)
    {
        $httpClient->request('DELETE', '/uri', Argument::any())->willReturn($response);
        $this->delete('/uri')->shouldReturn($response);
    }

    function it_should_send_request($httpClient, $response)
    {
        $httpClient->request('GET', '/uri', Argument::any())->willReturn($response);
        $this->send('GET', '/uri')->shouldReturn($response);
    }

    function it_should_send_request_with_custom_headers($httpClient, $response)
    {
        $query = ['foo' => 'bar'];
        $httpClient->request('GET', '/uri', Argument::withEntry(RequestOptions::QUERY, $query))->willReturn($response);
        $this->send('GET', '/uri', ['foo' => 'bar'])->shouldReturn($response);
    }

    function it_should_send_request_with_custom_body($httpClient, $response)
    {
        $httpClient->request('POST', '/uri', Argument::withEntry(RequestOptions::BODY, 'foo'))->willReturn($response);
        $this->send('POST', '/uri', [], 'foo')->shouldReturn($response);
    }

    function it_should_get_the_last_api_response($httpClient, $response)
    {
        $httpClient->request('GET', 'uri', Argument::any())->willReturn($response);
        $this->send('GET', 'uri');

        $this->getLastResponse()->shouldReturn($response);
    }

    function it_should_have_a_default_http_client()
    {
        $this->beConstructedWith('token');
        $this->getHttpClient()->shouldReturnAnInstanceOf(ClientInterface::class);
    }

    function it_validate_response_or_trigger_an_exception($httpClient, $response)
    {
        $response->getStatusCode()->willReturn(400);
        $response->getReasonPhrase()->willReturn('error message');

        $httpClient->request('GET', 'uri', Argument::any())->willReturn($response);
        $this->shouldThrow(new BadResponseException('error message', $response->getWrappedObject()))->duringSend('GET', 'uri');
    }
}
