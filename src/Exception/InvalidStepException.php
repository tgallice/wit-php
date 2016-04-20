<?php

namespace Tgallice\Wit\Exception;

class InvalidStepException extends \RuntimeException
{
    /**
     * @var array
     */
    private $stepData;

    /**
     * @param string $message
     * @param array $stepData
     */
    public function __construct($message, array $stepData = [])
    {
        parent::__construct($message);

        $this->stepData = $stepData;
    }

    /**
     * @return array
     */
    public function getStepData()
    {
        return $this->stepData;
    }
}
