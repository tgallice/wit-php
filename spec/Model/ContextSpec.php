<?php

namespace spec\Tgallice\Wit\Model;

use PhpSpec\ObjectBehavior;
use Tgallice\Wit\Model\Entity;
use Tgallice\Wit\Model\Location;

class ContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\Context');
    }

    function it_has_no_default_reference_time()
    {
        $this->getReferenceTime()->shouldReturn(null);
    }

    function it_can_define_a_reference_time()
    {
        $dt = new \DateTimeImmutable();
        $this->setReferenceTime($dt);
        $this->getReferenceTime()->shouldReturn($dt->format(DATE_ISO8601));
    }

    function it_has_no_default_location()
    {
        $this->getLocation()->shouldReturn([]);
    }

    function it_can_define_a_location(Location $location)
    {
        $this->beConstructedWith([
            'location' => $location,
        ]);
        $this->getLocation()->shouldReturn($location);
    }

    function it_has_no_default_state()
    {
        $this->getState()->shouldReturn(null);
    }

    function it_can_define_a_state()
    {
        $this->beConstructedWith([
            'state' => 'state',
        ]);
        $this->getState()->shouldReturn('state');
    }

    function it_has_no_default_entities()
    {
        $this->getEntities()->shouldReturn([]);
    }

    function it_can_define_entities(Entity $entity)
    {
        $this->beConstructedWith([
            'entities' => [$entity],
        ]);
        $this->getEntities()->shouldReturn([$entity]);
    }

    function it_has_no_default_timezone()
    {
        $this->getTimezone()->shouldReturn(null);
    }

    function it_can_define_a_timezone()
    {
        $timezone = 'Europe/Paris';
        $this->setTimezone($timezone);
        $this->getTimezone()->shouldReturn($timezone);
    }

    function it_can_add_custom_context_field()
    {
        $this->add('context', 'value');
        $this->get('context')->shouldReturn('value');
    }

    function it_can_remove_a_context_field()
    {
        $this->add('context', 'value');
        $this->get('context')->shouldReturn('value');
        $this->remove('context');
        $this->get('context')->shouldReturn(null);
    }

    function it_can_check_presence_of_a_context_field()
    {
        $this->add('context', 'value');
        $this->has('context')->shouldReturn(true);
        $this->has('wrong_context')->shouldReturn(false);
    }

    function it_can_check_if_context_is_empty()
    {
        $this->isEmpty()->shouldReturn(true);
        $this->add('context', 'value');
        $this->isEmpty()->shouldReturn(false);
    }

    function it_must_be_json_serializable()
    {
        $this->shouldHaveType(\JsonSerializable::class);
        $this->add('custom', 'value');
        $serialized = json_encode($this->getWrappedObject());
        $this->jsonSerialize()->shouldReturn(json_decode($serialized, true));
    }
}
