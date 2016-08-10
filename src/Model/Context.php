<?php

namespace Tgallice\Wit\Model;

class Context implements \JsonSerializable
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
     * @return string|null
     */
    public function getReferenceTime()
    {
        return $this->getContextField('reference_time');
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
     */
    public function add($name, $value)
    {
        $this->data[$name] = $value;
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
     */
    public function remove($name)
    {
        unset($this->data[$name]);
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
     * Check if the context is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->data);
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
