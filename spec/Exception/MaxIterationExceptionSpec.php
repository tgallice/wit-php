<?php

namespace spec\Tgallice\Wit\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MaxIterationExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Exception\MaxIterationException');
    }

    function it_a_runtime_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }
}
