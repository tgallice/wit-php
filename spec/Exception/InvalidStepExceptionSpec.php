<?php

namespace spec\Tgallice\Wit\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InvalidStepExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('error message');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Exception\InvalidStepException');
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
        $this->beConstructedWith('error message', ['step_data']);
        $this->getStepData()->shouldReturn(['step_data']);
    }

    function it_can_define_a_custom_error_message()
    {
        $this->getMessage()->shouldReturn('error message');
    }
}
