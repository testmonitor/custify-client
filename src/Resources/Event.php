<?php

namespace TestMonitor\Custify\Resources;

class Event extends Resource
{
    /**
     * The name of the event.
     *
     * @var string
     */
    public $name;

    /**
     * The time the event occurred in your application.
     *
     * @var string
     */
    public $created_at;

    /**
     * The person for which the event occurs.
     *
     * @var \TestMonitor\Custify\Resources\Person|null
     */
    public $person;

    /**
     * The company for which the event occurs.
     *
     * @var \TestMonitor\Custify\Resources\Company|null
     */
    public $company;

    /**
     * If present, subsequent events with the same identifier will be ignored.
     *
     * @var string
     */
    public $deduplication_id;

    /**
     * Meta data about this event.
     *
     * @var \TestMonitor\Custify\Resources\MetaData
     */
    public $metadata;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->name = $attributes['name'];

        $this->person = $attributes['person'] ?? null;
        $this->company = $attributes['company'] ?? null;

        $this->created_at = $attributes['created_at'] ?? date('c');

        $this->deduplication_id = $attributes['deduplication_id'] ?? '';

        $this->metadata = $attributes['metadata'] ?? new MetaData();
    }
}
