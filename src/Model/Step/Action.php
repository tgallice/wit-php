<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Action extends AbstractStep
{
    /**
     * @var string
     */
    private $action;

    /**
     * @param string $action
     * @param float $confidence
     * @param array $entities
     */
    public function __construct($action, $confidence, array $entities = [])
    {
        parent::__construct(Step::TYPE_ACTION, $confidence, $entities);
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
