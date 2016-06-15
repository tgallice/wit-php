<?php

namespace Tgallice\Wit\Model;

class EntityValue implements \JsonSerializable
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string[]
     */
    private $expressions;

    /**
     * @var null|string
     */
    private $metadata;

    /**
     * @param $value
     * @param string[] $expressions
     * @param null|string $metadata
     */
    public function __construct($value, array $expressions = [], $metadata = null)
    {
        $this->value = $value;
        $this->expressions = $expressions;
        $this->metadata = $metadata;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string[]
     */
    public function getExpressions()
    {
        return $this->expressions;
    }

    /**
     * @return null|string
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $data = [
            'value' => $this->value,
        ];

        if (!empty($this->expressions)) {
            $data['expressions'] = $this->expressions;
        }

        if (!empty($this->metadata)) {
            $data['metadata'] = $this->metadata;
        }

        return $data;
    }
}
