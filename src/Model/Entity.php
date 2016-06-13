<?php

namespace Tgallice\Wit\Model;

class Entity implements \JsonSerializable
{
    const LOOKUP_TRAIT = 'trait';

    const LOOKUP_KEYWORDS = 'keywords';

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
     * @var string[]
     */
    private $lookups;

    /**
     * @param $id
     * @param array|EntityValue[] $values
     * @param string|null $description
     * @param string[] $lookups
     */
    public function __construct($id, array $values = [], $description = '', array $lookups = [Entity::LOOKUP_KEYWORDS])
    {
        $this->id = $id;
        $this->values = $values;
        $this->description = $description;
        $this->lookups = $lookups;
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
     * @return array|EntityValue[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return string[]
     */
    public function getLookups()
    {
        return $this->lookups;
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
            'lookups' => $this->lookups,
        ];
    }
}
