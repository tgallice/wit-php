<?php

namespace spec\Tgallice\Wit\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Model\Step;

class BadResponseExceptionSpec extends ObjectBehavior
{
    function let(ResponseInterface $response)
    {
        $this->beConstructedWith('error message', $response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Exception\BadResponseException');
    }

    function it_is_a_runtime_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }

    function it_should_have_a_response_object($response)
    {
        $this->getResponse()->shouldReturn($response);
    }

    function it_define_the_error_code_by_the_response_status_code($response)
    {
        $response->getStatusCode()->willReturn(400);
        $this->beConstructedWith('error message', $response);

        $this->getCode()->shouldReturn(400);
    }

    function it_can_define_a_custom_error_message()
    {
        $this->getMessage()->shouldReturn('error message');
    }
}
