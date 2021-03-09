<?php

namespace TestMonitor\Custify\Actions;

use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Transforms\TransformsPeople;
use TestMonitor\Custify\Exceptions\NotFoundException;

trait ManagesPeople
{
    use TransformsPeople;

    /**
     * Get a list of of people.
     *
     * @param int $page
     * @param int $limit
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Person[]
     */
    public function people($page = 1, $limit = 10): array
    {
        $response = $this->get('people/all', [
            'query' => ['itemsPerPage' => $limit, 'page' => $page],
        ]);

        return $this->fromCustifyPeople($response[0]['people']);
    }

    /**
     * Get a single person.
     *
     * @param string $id
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Person
     */
    public function person(string $id): Person
    {
        $response = $this->get("people/{$id}");

        return $this->fromCustifyPerson($response);
    }

    /**
     * Get a single person by user id.
     *
     * @param string $userId
     *
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Person
     */
    public function personByUserId(string $userId): Person
    {
        $response = $this->get("people?user_id={$userId}");

        Validator::keysExists($response[0], ['people']);

        // Simulate a not found response when the user_id does not exists.
        if (empty($response[0]['people'])) {
            throw new NotFoundException();
        }

        return $this->fromCustifyPerson($response[0]['people'][0]);
    }

    /**
     * Get a single person by email.
     *
     * @param string $email
     *
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Person
     */
    public function personByEmail(string $email): Person
    {
        $response = $this->get("people?email={$email}");

        Validator::keysExists($response[0], ['people']);

        // Simulate a not found response when the user_id does not exists.
        if (empty($response[0]['people'])) {
            throw new NotFoundException();
        }

        return $this->fromCustifyPerson($response[0]['people'][0]);
    }

    /**
     * Create or update a person.
     *
     * @param \TestMonitor\Custify\Resources\Person $person
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Person
     */
    public function createOrUpdatePerson(Person $person): Person
    {
        $response = $this->post('people', ['json' => $this->toCustifyPerson($person)]);

        return $this->fromCustifyPerson($response);
    }

    /**
     * Delete a person.
     *
     * @param \TestMonitor\Custify\Resources\Person $person
     *
     * @return bool
     */
    public function deletePerson(Person $person)
    {
        $response = $this->delete("people/{$person->id}");

        return (bool) ($response['deleted'] ?? false);
    }
}
