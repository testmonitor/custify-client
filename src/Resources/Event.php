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
     * The time the event occurred in your application..
     *
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $user_id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $company_id;

    /**
     * @var string
     */
    public $deduplication_id;

    /**
     * @var array
     */
    public $metadata;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->name = $attributes['name'] ?? '';
        $this->created_at = $attributes['created_at'] ?? (new \DateTime());

        $this->deduplication_id = $attributes['deduplication_id'] ?? '';

        $this->metadata = $attributes['metadata'] ?? [];

        if (array_key_exists('user_id', $attributes)) {
            $this->user_id = $attributes['user_id'];
        } elseif (array_key_exists('email', $attributes)) {
            $this->email = $attributes['email'];
        } elseif (array_key_exists('company_id', $attributes)) {
            $this->company_id = $attributes['company_id'];
        }
    }
}
