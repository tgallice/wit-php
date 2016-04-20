<?php

namespace spec\Tgallice\Wit\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\Model\Context;

class ConversationExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('error message');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Exception\ConversationException');
    }

    function it_is_a_runtime_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }


    function it_step_data_is_a_empty_array_by_default()
    {
        $this->getStepData()->shouldReturn([]);
    }

    function it_can_have_step_data()
    {
        $this->beConstructedWith('error message', null, null, ['step_data']);
        $this->getStepData()->shouldReturn(['step_data']);
    }

    function it_has_no_context_by_default()
    {
        $this->getContext()->shouldReturn(null);
    }

    function it_can_have_a_context(Context $context)
    {
        $this->beConstructedWith('error message', null, $context);
        $this->getContext()->shouldReturn($context);
    }

    function it_has_no_session_id_by_default()
    {
        $this->getSessionId()->shouldReturn(null);
    }

    function it_can_have_a_session_id()
    {
        $this->beConstructedWith('error message', 'session id');
        $this->getSessionId()->shouldReturn('session id');
    }

    function it_can_define_a_custom_error_message()
    {
        $this->getMessage()->shouldReturn('error message');
    }
}
