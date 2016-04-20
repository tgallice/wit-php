<?php

namespace spec\Tgallice\Wit\Model\Step;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\Model\Step;

class StopSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\Step\Stop');
    }

    function it_is_a_step()
    {
        $this->shouldImplement('Tgallice\Wit\Model\Step');
    }

    function it_has_a_confidence()
    {
        $this->getConfidence()->shouldReturn(1.0);
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(Step::TYPE_STOP);
    }
}
