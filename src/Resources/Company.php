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
     * The website of the company.
     *
     * @var string
     */
    public $website;

    /**
     * The industry of the company.
     *
     * @var string
     */
    public $industry;

    /**
     * The size of the company.
     *
     * @var integer
     */
    public $size;

    /**
     * The plan of the company.
     *
     * @var string
     */
    public $plan;

    /**
     * The custom attributes for this person.
     *
     * @var \TestMonitor\Custify\Resources\CustomAttributes
     */
    public $customAttributes;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? '';
        $this->company_id = $attributes['company_id'] ?? '';
        $this->name = $attributes['name'] ?? '';
        $this->website = $attributes['website'] ?? null;
        $this->industry = $attributes['industry'] ?? null;
        $this->size = $attributes['size'] ?? null;
        $this->plan = $attributes['plan'] ?? null;

        $this->customAttributes = $attributes['custom_attributes'] ?? new CustomAttributes();
    }
}
