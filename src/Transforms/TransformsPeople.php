<?php

namespace TestMonitor\Custify\Transforms;

use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Resources\CustomAttributes;

trait TransformsPeople
{
    /**
     * @param array $people
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     *
     * @return \TestMonitor\Custify\Resources\Person[]
     */
    protected function fromCustifyPeople($people): array
    {
        Validator::isArray($people);

        return array_map(function ($person) {
            return $this->fromCustifyPerson($person);
        }, $people);
    }

    /**
     * @param array $person
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     *
     * @return \TestMonitor\Custify\Resources\Person
     */
    protected function fromCustifyPerson($person): Person
    {
        Validator::keysExists($person, ['id', 'user_id', 'email']);

        return new Person([
            'id' => $person['id'],
            'user_id' => $person['user_id'],
            'email' => $person['email'],
            'name' => $person['name'] ?? '',
            'signed_up_at' => $person['signed_up_at'] ?? '',
            'phone' => $person['phone'] ?? '',
            'unsubscribed_from_emails' => $person['unsubscribed_from_emails'] ?? false,
            'unsubscribed_from_calls' => $person['unsubscribed_from_calls'] ?? false,

            'custom_attributes' => new CustomAttributes($person['custom_attributes'] ?? []),

            'companies' => array_map(function ($company) {
                return new Company(['id' => $company]);
            }, $person['companies'] ?? []),
        ]);
    }

    /**
     * @param Person $person
     * @return array
     */
    protected function toCustifyPerson(Person $person): array
    {
        return array_filter([
            'user_id' => $person->user_id,
            'email' => $person->email,
            'name' => $person->name,
            'signed_up_at' => $person->signedUpAt,
            'phone' => $person->phone,
            'unsubscribed_from_emails' => $person->unsubscribedFromEmails,
            'unsubscribed_from_calls' => $person->unsubscribedFromCalls,

            'custom_attributes' => $person->customAttributes->toArray(),

            'companies' => array_map(function (Company $company) {
                return ['company_id' => $company->company_id];
            }, $person->companies ?? []),
        ], fn ($value) => is_bool($value) || ! empty($value));
    }
}
