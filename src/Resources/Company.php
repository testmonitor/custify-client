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
     * The company signup date or date the contract started.
     *
     * @var string
     */
    public $signedUpAt;

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
     * @var int
     */
    public $size;

    /**
     * The plan of the company.
     *
     * @var string
     */
    public $plan;

    /**
     * The churn state of the company.
     *
     * @var bool
     */
    public $churned;

    /**
     * The email address of the Customer Success Manager.
     *
     * @var string
     */
    public $ownersCsm;

    /**
     * The email address of the Account manager.
     *
     * @var string
     */
    public $ownersAccount;

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
        $this->website = $attributes['website'] ?? '';
        $this->industry = $attributes['industry'] ?? '';
        $this->size = $attributes['size'] ?? '';
        $this->plan = $attributes['plan'] ?? '';
        $this->churned = $attributes['churned'] ?? '';
        $this->ownersAccount = $attributes['owners_account'] ?? '';
        $this->ownersCsm = $attributes['owners_csm'] ?? '';
        $this->signedUpAt = $attributes['signed_up_at'] ?? '';

        $this->customAttributes = $attributes['custom_attributes'] ?? new CustomAttributes();
    }
}
