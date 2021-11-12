<?php

namespace TestMonitor\Custify\Resources;

class CustomAttributes extends Resource
{
    /**
     * The attributes.
     *
     * @var array
     */
    public $attributes;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->attributes = array_filter($attributes, fn ($value) => is_bool($value) || ! empty($value));
    }

    /**
     * Gets a custom attribute.
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Sets a custom attribute.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Returns the custom attributes as a key/value array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
