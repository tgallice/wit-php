<?php

namespace Tgallice\Wit\Model;

class Entity implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    private $description;

    /**
     * @param $id
     * @param array $values
     * @param string|null $description
     */
    public function __construct($id, array $values = [], $description = '')
    {
        $this->id = $id;
        $this->values = $values;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'values' => $this->values,
            'doc' => $this->description,
        ];
    }
}
