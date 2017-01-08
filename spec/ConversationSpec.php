<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\ActionMapping;
use Tgallice\Wit\ConverseApi;
use Tgallice\Wit\Exception\ConversationException;
use Tgallice\Wit\Exception\MaxIterationException;
use Tgallice\Wit\Model\Context;
use Tgallice\Wit\Model\Step;
use Tgallice\Wit\Model\Step\Action;
use Tgallice\Wit\Model\Step\Merge;
use Tgallice\Wit\Model\Step\Message;
use Tgallice\Wit\Model\Step\Stop;

class ConversationSpec extends ObjectBehavior
{
    private $stepData = [
        Step::TYPE_ACTION => [
            'type' => Step::TYPE_ACTION,
            'action' => 'actionToExecute',
            'confidence' => 1,
        ],
        Step::TYPE_MERGE => [
            'type' => Step::TYPE_MERGE,
            'entities' => ['name' => 'value'],
            'confidence' => 1,
        ],
        Step::TYPE_STOP => [
            'type' => Step::TYPE_STOP,
            'confidence' => 1,
        ],
        Step::TYPE_MESSAGE => [
            'type' => Step::TYPE_MESSAGE,
            'msg' => 'message',
            'confidence' => 1,
        ]
    ];

    function let(ConverseApi $api, ActionMapping $actionMapping)
    {
        $this->beConstructedWith($api, $actionMapping);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Conversation');
    }

    function it_converse_automatically_and_return_the_last_context($api, $actionMapping)
    {
        $api->converse('session_id', 'my text', Argument::any())->willReturn($this->stepData[Step::TYPE_MERGE]);

        $expectedContext = new Context();
        $expectedContext->add('custom', 'value');
        $data = $this->stepData[Step::TYPE_MERGE];

        $actionMapping
            ->merge('session_id', Argument::type(Context::class), Argument::that(function (Merge $step) use ($data) {
                return $step->getEntities() === $data['entities'];
            }))
            ->willReturn($expectedContext);


        $api->converse('session_id', null, $expectedContext)->willReturn($this->stepData[Step::TYPE_MESSAGE]);

        $data = $this->stepData[Step::TYPE_MESSAGE];
        $actionMapping
            ->say(
                'session_id',
                Argument::type(Context::class),
                Argument::that(function (Message $step) use ($data) {
                    return $step->getMessage() === $data['msg'];
                })
            )
            ->willReturn($expectedContext);

        $api->converse('session_id', null, $expectedContext)->willReturn($this->stepData[Step::TYPE_STOP]);
        $actionMapping
            ->stop('session_id', Argument::type(Context::class), Argument::type(Stop::class))
            ->shouldBeCalled();

        $context = new Context();

        $this->converse('session_id', 'my text', $context)->shouldReturn($expectedContext);
    }

    function it_stop_converse_if_max_step_exceeded($api, $actionMapping)
    {

        $context = new Context();

        // First step
        $api->converse('session_id', 'my text', $context)->willReturn($this->stepData[Step::TYPE_MERGE]);

        $expectedContext = new Context($this->stepData[Step::TYPE_MERGE]['entities']);

        $actionMapping->merge('session_id', $context, Argument::type(Merge::class))->willReturn($expectedContext);
        $api->converse('session_id', null, $expectedContext)->shouldNotBeCalled();

        // Trigger the error on max iteration
        $actionMapping
            ->error(
                'session_id',
                $expectedContext,
                Argument::type(MaxIterationException::class)
            )
            ->shouldBeCalled();

        $this->converse('session_id', 'my text', $context, 1)->shouldReturn($expectedContext);
    }

    function it_delegate_the_action_to_execute($api, $actionMapping) {
        // First step
        $api->converse('session_id', null, Argument::type(Context::class))->willReturn($this->stepData[Step::TYPE_ACTION]);
        $data = $this->stepData[Step::TYPE_ACTION];

        $actionMapping->action('session_id', Argument::type(Context::class), Argument::that(function (Action $step) use ($data) {
            return $step->getAction() === $data['action'];
        }))->willReturn(new Context());

        // Second Step
        $api->converse('session_id', null, Argument::type(Context::class))->willReturn($this->stepData[Step::TYPE_STOP]);
        $actionMapping->stop('session_id', Argument::type(Context::class), Argument::type(Stop::class))->shouldBeCalled();

        $this->converse('session_id');
    }

    function it_trigger_error_action_on_malformed_step($api, $actionMapping)
    {
        $api->converse('session_id', null, Argument::type(Context::class))->willReturn(null);
        $actionMapping
            ->error(
                'session_id',
                Argument::type(Context::class),
                Argument::type(ConversationException::class),
                Argument::type('array')
            )
            ->shouldBeCalled();

        $this->converse('session_id')->shouldBeLike(new Context());
    }

    function it_trigger_error_action_if_error_returned_from_the_api($api, $actionMapping)
    {
        $api->converse('session_id', null, Argument::type(Context::class))->willReturn([
            'error' => 'error message'
        ]);
        $actionMapping
            ->error(
                'session_id',
                Argument::type(Context::class),
                Argument::type(ConversationException::class),
                Argument::type('array')
            )
            ->shouldBeCalled();

        $this->converse('session_id')->shouldBeLike(new Context());
    }

    function it_delegate_action_step_execution($api, $actionMapping)
    {
        $api->converse('session_id', 'my text', Argument::any())->willReturn($this->stepData[Step::TYPE_ACTION]);

        $expectedContext = new Context();
        $expectedContext->add('custom', 'value');

        $actionMapping
            ->action('session_id', Argument::type(Context::class),  Argument::type(Action::class))
            ->willReturn($expectedContext);

        $api->converse('session_id', null, $expectedContext)->willReturn($this->stepData[Step::TYPE_STOP]);
        $actionMapping
            ->stop('session_id', $expectedContext, Argument::type(Stop::class))
            ->shouldBeCalled();

        $this->converse('session_id', 'my text')->shouldReturn($expectedContext);
    }

    function it_delegate_stop_execution($api, $actionMapping)
    {
        $api->converse('session_id', 'my text', Argument::any())->willReturn($this->stepData[Step::TYPE_STOP]);
        $actionMapping
            ->stop('session_id', Argument::type(Context::class), Argument::type(Stop::class))
            ->shouldBeCalled();

        $context = new Context();

        $this->converse('session_id', 'my text', $context)->shouldReturn($context);
    }

    function it_delegate_message_step_execution($api, $actionMapping)
    {
        $data = $this->stepData[Step::TYPE_MESSAGE];
        $api->converse('session_id', 'my text', Argument::any())->willReturn($data);
        $actionMapping
            ->say('session_id', Argument::type(Context::class), Argument::that(function (Message $step) use ($data) {
                return $step->getMessage() === $data['msg'];
            }))
            ->shouldBeCalled();

        $api->converse('session_id', null, Argument::type(Context::class))->willReturn($this->stepData[Step::TYPE_STOP]);
        $actionMapping
            ->stop('session_id', Argument::type(Context::class), Argument::type(Stop::class))
            ->shouldBeCalled();

        $context = new Context();

        $this->converse('session_id', 'my text', $context)->shouldReturn($context);
    }

    function it_delegate_merge_execution($api, $actionMapping)
    {
        $api->converse('session_id', 'my text', Argument::any())->willReturn($this->stepData[Step::TYPE_MERGE]);

        $expectedContext = new Context();
        $expectedContext->add('custom', 'value');

        $actionMapping
            ->merge('session_id', Argument::type(Context::class), Argument::type(Merge::class))
            ->willReturn($expectedContext);

        $api->converse('session_id', null, $expectedContext)->willReturn($this->stepData[Step::TYPE_STOP]);
        $actionMapping
            ->stop('session_id', Argument::type(Context::class), Argument::type(Stop::class))
            ->shouldBeCalled();

        $this->converse('session_id', 'my text')->shouldReturn($expectedContext);
    }
}
