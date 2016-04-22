<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Client;
use Tgallice\Wit\Exception\BadResponseException;
use Tgallice\Wit\HttpClient\HttpClient;

class ClientSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, ResponseInterface $response)
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
        $httpClient->send('POST', '/uri', ['obj' => 'value'], [], Argument::any(), [])->willReturn($response);
        $this->post('/uri', ['obj' => 'value'])->shouldReturn($response);
    }

    function it_has_shortcut_get_request($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, ['q' => 'text'], Argument::any(), [])->willReturn($response);
        $this->get('/uri', ['q' => 'text'])->shouldReturn($response);
    }

    function it_has_shortcut_put_request($httpClient, $response)
    {
        $httpClient->send('PUT', '/uri', ['obj' => 'value'], [], Argument::any(), [])->willReturn($response);
        $this->put('/uri', ['obj' => 'value'])->shouldReturn($response);
    }

    function it_has_shortcut_delete_request($httpClient, $response)
    {
        $httpClient->send('DELETE', '/uri', null, [], Argument::any(), [])->willReturn($response);
        $this->delete('/uri')->shouldReturn($response);
    }

    function it_should_send_request($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, [], Argument::any(), [])->willReturn($response);
        $this->send('GET', '/uri')->shouldReturn($response);
    }

    function it_should_send_request_with_custom_headers($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, [], Argument::withEntry('foo', 'bar'), [])->willReturn($response);
        $this->send('GET', '/uri', null, [], ['foo' => 'bar'])->shouldReturn($response);
    }

    function it_should_send_request_with_custom_query($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, ['foo' => 'bar'], Argument::any(), [])->willReturn($response);
        $this->send('GET', '/uri', null, ['foo' => 'bar'])->shouldReturn($response);
    }

    function it_should_send_request_with_custom_body($httpClient, $response)
    {
        $httpClient->send('POST', '/uri', 'foo', [], Argument::any(), [])->willReturn($response);
        $this->send('POST', '/uri', 'foo')->shouldReturn($response);
    }

    function it_should_send_request_with_custom_options($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, [], Argument::any(), ['timeout' => 10])->willReturn($response);
        $this->send('GET', '/uri', null, [], [], ['timeout' => 10])->shouldReturn($response);
    }

    function it_must_contain_the_authorization_header($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, [], Argument::withEntry('Authorization', 'Bearer token'), [])->willReturn($response);
        $this->send('GET', '/uri')->shouldReturn($response);
    }

    function it_should_get_the_last_api_response($httpClient, $response)
    {
        $httpClient->send('GET', 'uri', null, [], Argument::any(), [])->willReturn($response);
        $this->send('GET', 'uri');

        $this->getLastResponse()->shouldReturn($response);
    }

    function it_should_have_a_default_http_client()
    {
        $this->beConstructedWith('token');
        $this->getHttpClient()->shouldReturnAnInstanceOf(HttpClient::class);
    }

    function it_validate_response_or_trigger_an_exception($httpClient, $response)
    {
        $response->getStatusCode()->willReturn(400);
        $response->getReasonPhrase()->willReturn('error message');

        $httpClient->send('GET', '/uri', null, [], Argument::any(), [])->willReturn($response);
        $this->shouldThrow(new BadResponseException('error message', $response->getWrappedObject()))->duringSend('GET', '/uri');
    }

    function it_validate_method_or_trigger_an_exception()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringSend('Custom', 'uri');
    }

    function it_can_define_api_version($httpClient, $response)
    {
        $this->beConstructedWith('token', $httpClient, '20160430');
        $httpClient->send('GET', '/uri', null, [], Argument::withEntry('Accept', 'application/vnd.wit.'.Client::DEFAULT_API_VERSION.'+json'), [])->shouldNotBeCalled();
        $httpClient->send('GET', '/uri', null, [], Argument::withEntry('Accept', 'application/vnd.wit.20160430+json'), [])->willReturn($response);

        $this->get('/uri');
    }

    function it_has_a_default_api_version($httpClient, $response)
    {
        $httpClient->send('GET', '/uri', null, [], Argument::withEntry('Accept', 'application/vnd.wit.'.Client::DEFAULT_API_VERSION.'+json'), [])->willReturn($response);

        $this->get('/uri');
    }
}
