<?php

namespace TestMonitor\Custify\Resources;

class MetaData extends Resource
{
    /**
     * The metadata.
     *
     * @var array
     */
    public $metadata;

    /**
     * Create a new resource instance.
     *
     * @param array $metadata
     */
    public function __construct($metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * Gets a meta data item.
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->metadata[$name] ?? null;
    }

    /**
     * Sets a meta data item.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->metadata[$name] = $value;
    }

    /**
     * Returns the custom attributes as a key/value array.
     *
     * @return object
     */
    public function toObject(): object
    {
        return (object) $this->metadata;
    }
}
