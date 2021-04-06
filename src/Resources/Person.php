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
     * The name of the person.
     *
     * @var string
     */
    public $name;

    /**
     * The person's phone number.
     *
     * @var string
     */
    public $phone;

    /**
     * The person's signup date or date the contract started.
     *
     * @var string
     */
    public $signedUpAt;

    /**
     * The person's unsubscribed status from emails.
     *
     * @var bool
     */
    public $unsubscribedFromEmails;

    /**
     * The person's unsubscribed status from calls.
     *
     * @var bool
     */
    public $unsubscribedFromCalls;

    /**
     * The companies related to this person.
     *
     * @var array
     */
    public $companies;

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
        $this->user_id = $attributes['user_id'] ?? '';
        $this->email = $attributes['email'];
        $this->name = $attributes['name'] ?? '';
        $this->phone = $attributes['phone'] ?? '';
        $this->signedUpAt = $attributes['signed_up_at'] ?? '';

        $this->unsubscribedFromEmails = $attributes['unsubscribed_from_emails'] ?? false;
        $this->unsubscribedFromCalls = $attributes['unsubscribed_from_calls'] ?? false;

        $this->customAttributes = $attributes['custom_attributes'] ?? new CustomAttributes();

        $this->companies = $attributes['companies'] ?? [];
    }
}
