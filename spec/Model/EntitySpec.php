<?php

namespace spec\Tgallice\Wit\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\Model\Entity;
use Tgallice\Wit\Model\EntityValue;

class EntitySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('id', [], 'description');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\Entity');
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('id');
    }

    function it_has_a_description()
    {
        $this->getDescription()->shouldReturn('description');
    }

    function it_has_values(EntityValue $entityValue)
    {
        $this->beConstructedWith('id', [$entityValue]);
        $this->getValues()->shouldEqual([$entityValue]);
    }

    function it_has_a_lookups()
    {
        $this->getLookups()->shouldReturn([Entity::LOOKUP_KEYWORDS]);
    }

    function it_must_be_json_serializable()
    {
        $this->shouldHaveType(\JsonSerializable::class);
        $serialized = json_encode($this->getWrappedObject());
        $this->jsonSerialize()->shouldReturn(json_decode($serialized, true));
    }
}
