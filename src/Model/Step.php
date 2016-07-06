<?php

namespace Tgallice\Wit\Model;


interface Step
{
    const TYPE_ACTION = 'action';
    const TYPE_MERGE = 'merge';
    const TYPE_MESSAGE = 'msg';
    const TYPE_STOP = 'stop';

    /**
     * @return string
     */
    public function getType();

    /**
     * @return float
     */
    public function getConfidence();

    /**
     * @return array
     */
    public function getEntities();
}
