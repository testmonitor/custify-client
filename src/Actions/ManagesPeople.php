<?php

namespace TestMonitor\Custify\Actions;

use TestMonitor\Custify\Transforms\TransformsPeople;

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
    public function people($page = 1, $limit = 10)
    {
        $response = $this->get('people/all', [
            'query' => ['itemsPerPage' => $limit, 'page' => $page],
        ]);

        return $this->fromCustifyPeople($response);
    }
}
