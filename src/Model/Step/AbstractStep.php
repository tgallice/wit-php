<?php
/**
 * @author Hannes Finck <finck.hannes@gmail.com>
 */

namespace Tgallice\Wit\Model\Step;


use Tgallice\Wit\Model\Step;

abstract class AbstractStep implements Step
{
    /**
     * @var float
     */
    private $confidence;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $entities;

    /**
     * AbstractStep constructor.
     * @param string $type
     * @param float $confidence
     * @param array $entities
     */
    public function __construct($type, $confidence, array $entities = [])
    {
        $this->type = $type;
        $this->confidence = (float) $confidence;
        $this->entities = $entities;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getConfidence()
    {
        return $this->confidence;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }
}
