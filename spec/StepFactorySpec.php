<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\Model\Step;
use Tgallice\Wit\Model\Step\Action;
use Tgallice\Wit\Model\Step\Merge;
use Tgallice\Wit\Model\Step\Stop;
use Tgallice\Wit\Model\Step\Message;

class StepFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\StepFactory');
    }

    function it_create_action_step()
    {
        $step = new Action('actionName', 1);

        $this::createActionStep([
            'type' => Step::TYPE_ACTION,
            'action' => 'actionName',
            'confidence' => 1,
        ])->shouldBeLike($step);
    }

    function it_create_merge_step()
    {
        $step = new Merge(['entities'], 1);

        $this::createMergeStep([
            'type' => Step::TYPE_MERGE,
            'entities' => ['entities'],
            'confidence' => 1,
        ])->shouldBeLike($step);
    }

    function it_create_message_step()
    {
        $step = new Message('message', 1);

        $this::createMessageStep([
            'type' => Step::TYPE_MESSAGE,
            'msg' => 'message',
            'confidence' => 1,
        ])->shouldBeLike($step);
    }

    function it_create_stop_step()
    {
        $step = new Stop(1);

        $this::createStopStep([
            'type' => Step::TYPE_STOP,
            'confidence' => 1,
        ])->shouldBeLike($step);
    }

    function it_should_guess_and_create_step()
    {
        $stop = [
            'type' => Step::TYPE_STOP,
            'confidence' => 1,
        ];

        $merge = [
            'type' => Step::TYPE_MERGE,
            'entities' => ['entities'],
            'confidence' => 1,
        ];

        $action = [
            'type' => Step::TYPE_ACTION,
            'action' => 'actionName',
            'confidence' => 1,
        ];

        $message = [
            'type' => Step::TYPE_MESSAGE,
            'msg' => 'message',
            'confidence' => 1,
        ];

        $this::create($stop)->shouldBeAnInstanceOf(Stop::class);
        $this::create($message)->shouldBeAnInstanceOf(Message::class);
        $this::create($merge)->shouldBeAnInstanceOf(Merge::class);
        $this::create($action)->shouldBeAnInstanceOf(Action::class);
    }
}
