<?php

namespace TestMonitor\Custify\Transforms;

use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Person;

trait TransformsPeople
{
    /**
     * @param array $people
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
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
     * @return \TestMonitor\Custify\Resources\Person
     */
    protected function fromCustifyPerson($person): Person
    {
        Validator::keysExists($person, ['id', 'user_id']);

        return new Person([
            'id' => $person['id'],
            'user_id' => $person['user_id'],
            'email' => $person['email'],
        ]);
    }
}
