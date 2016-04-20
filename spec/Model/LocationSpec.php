<?php

namespace spec\Tgallice\Wit\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(37.42, -122.15);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\Location');
    }

    function it_has_latitude()
    {
        $this->getLatitude()->shouldReturn(37.42);
    }

    function it_has_longitude()
    {
        $this->getLongitude()->shouldReturn(-122.15);
    }

    function it_complains_if_no_latitude_provided()
    {
        $this->beConstructedWith(null, 12);
        $this->shouldThrow(new \InvalidArgumentException('A latitude and longitude must be defined'))->duringInstantiation();
    }

    function it_complains_if_no_longitude_provided()
    {
        $this->beConstructedWith(12, null);
        $this->shouldThrow(new \InvalidArgumentException('A latitude and longitude must be defined'))->duringInstantiation();
    }

    function its_json_serializable()
    {
        $this->shouldHaveType(\JsonSerializable::class);
        $serialized = json_encode($this->getWrappedObject());
        $this->jsonSerialize()->shouldReturn(json_decode($serialized, true));
    }
}

