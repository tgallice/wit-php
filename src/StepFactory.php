<?php

namespace Tgallice\Wit;

use Tgallice\Wit\Exception\InvalidStepException;
use Tgallice\Wit\Model\Step;
use Tgallice\Wit\Model\Step\Action;
use Tgallice\Wit\Model\Step\Merge;
use Tgallice\Wit\Model\Step\Message;
use Tgallice\Wit\Model\Step\Stop;

class StepFactory
{
    public static function create(array $step)
    {
        $type = isset($step['type']) ? $step['type'] : null;

        switch ($type) {
            case Step::TYPE_ACTION:
                return self::createActionStep($step);
            case Step::TYPE_MESSAGE:
                return self::createMessageStep($step);
            case Step::TYPE_MERGE:
                return self::createMergeStep($step);
            case Step::TYPE_STOP:
                return self::createStopStep($step);
            default:
                throw new InvalidStepException('Invalid Step', $step);
        }
    }

    /**
     * @param array $step
     *
     * @return array
     */
    private static function getEntitiesFromStepData(array $step = [])
    {
        return isset($step['entities']) ? $step['entities'] : [];
    }

    /**
     * @param array $step
     *
     * @return Action
     */
    public static function createActionStep(array $step)
    {
        return new Action($step['action'], $step['confidence'], self::getEntitiesFromStepData($step));
    }

    /**
     * @param array $step
     *
     * @return Merge
     */
    public static function createMergeStep(array $step)
    {
        return new Merge(self::getEntitiesFromStepData($step), $step['confidence']);
    }

    /**
     * @param array $step
     *
     * @return Message
     */
    public static function createMessageStep(array $step)
    {
        return new Message($step['msg'], $step['confidence'], self::getEntitiesFromStepData($step));
    }

    /**
     * @param array $step
     *
     * @return Stop
     */
    public static function createStopStep(array $step)
    {
        return new Stop($step['confidence']);
    }
}
