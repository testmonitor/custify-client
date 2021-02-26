<?php

namespace TestMonitor\Custify\Resources;

class Person extends Resource
{
    /**
     * The (internal) id of the person.
     *
     * @var string
     */
    public $id;

    /**
     * The (external) id of the person.
     *
     * @var string
     */
    public $user_id;

    /**
     * The email address of the person.
     *
     * @var string
     */
    public $email;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? '';
        $this->user_id = $attributes['user_id'];
        $this->email = $attributes['email'];
    }
}
