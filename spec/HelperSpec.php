<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Helper');
    }

    function it_get_first_entity_value()
    {
        $entities = json_decode('{
            "metric" : [ {
              "metadata" : "{\'code\' : 324}",
              "value" : "metric_visitor"
            } ],
            "datetime" : [ {
                "value" : {
                    "from" : "2014-07-01T00:00:00.000-07:00",
                    "to" : "2014-07-02T00:00:00.000-07:00"
                }
              }, {
                "value" : {
                    "from" : "2014-07-04T00:00:00.000-07:00",
                    "to" : "2014-07-05T00:00:00.000-07:00"
                }
              } ]
        }', true);

        $this::getFirstEntityValue('metric', $entities)->shouldReturn('metric_visitor');
        $this::getFirstEntityValue('datetime', $entities)->shouldReturn([
            'from' => '2014-07-01T00:00:00.000-07:00',
            'to' => '2014-07-02T00:00:00.000-07:00',
        ]);
    }

    function it_return_null_if_can_notget_first_entity_value()
    {
        $this::getFirstEntityValue('metric', [])->shouldReturn(null);
    }
}
