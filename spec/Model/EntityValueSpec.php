<?php

namespace spec\Tgallice\Wit\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityValueSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('value', ['expressions'], 'metadata');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\EntityValue');
    }

    function it_has_a_value()
    {
        $this->getValue()->shouldReturn('value');
    }

    function it_has_expressions()
    {
        $this->getExpressions()->shouldReturn(['expressions']);
    }

    function it_has_metadata()
    {
        $this->getMetadata()->shouldReturn('metadata');
    }

    function it_must_be_json_serializable()
    {
        $this->shouldHaveType(\JsonSerializable::class);
        $serialized = json_encode($this->getWrappedObject());
        $this->jsonSerialize()->shouldReturn(json_decode($serialized, true));
    }
}
