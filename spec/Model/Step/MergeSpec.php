<?php

namespace spec\Tgallice\Wit\Model\Step;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\Model\Step;

class MergeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['entities'], 1);
    }

    function it_is_a_step()
    {
        $this->shouldImplement('Tgallice\Wit\Model\Step');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\Step\Merge');
    }

    function it_has_entities()
    {
        $this->getEntities()->shouldReturn(['entities']);
    }

    function it_has_a_confidence()
    {
        $this->getConfidence()->shouldReturn(1.0);
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(Step::TYPE_MERGE);
    }
}
