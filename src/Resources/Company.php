<?php

namespace TestMonitor\Custify\Resources;

class Company extends Resource
{
    /**
     * The (internal) id of the company.
     *
     * @var string
     */
    public $id;

    /**
     * The (external) id of the company.
     *
     * @var string
     */
    public $company_id;

    /**
     * The name of the company.
     *
     * @var string
     */
    public $name;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'];
        $this->company_id = $attributes['company_id'];
        $this->name = $attributes['name'];
    }
}
