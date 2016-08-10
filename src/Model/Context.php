<?php

namespace Tgallice\Wit\Model;

/**
 * Context Value Object
 */
final class Context implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        // Ensure the refenre_date field
        if (empty($data['reference_date'])) {
            $data['reference_date'] = new \DateTimeImmutable();
        }

        if ($data['reference_date'] instanceof \DateTimeInterface) {
            $data['reference_date'] = $data['reference_date']->format(DATE_ISO8601);
        }

        $this->data = $data;
    }

    /**
     * @return array|Entity[]
     */
    public function getEntities()
    {
        return $this->getContextField('entities', []);
    }

    /**
     * @return array|Location
     */
    public function getLocation()
    {
        return $this->getContextField('location', []);
    }

    /**
     * Return the reference date in the ISO8601 format
     *
     * @return string
     */
    public function getReferenceDate()
    {
        return $this->getContextField('reference_date');
    }

    /**
     * @return array|string|null
     */
    public function getState()
    {
        return $this->getContextField('state');
    }

    /**
     * @return string|null
     */
    public function getTimezone()
    {
        return $this->getContextField('timezone');
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return Context
     */
    public function add($name, $value)
    {
        $newData = $this->data;
        $newData[$name] = $value;

        return new self($newData);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->getContextField($name);
    }

    /**
     * @param string $name
     *
     * @return Context
     */
    public function remove($name)
    {
        $newData = $this->data;
        unset($newData[$name]);

        return new self($newData);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    private function getContextField($name, $default = null)
    {
        return $this->has($name) ? $this->data[$name] : $default;
    }
}
