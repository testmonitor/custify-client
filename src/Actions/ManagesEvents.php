<?php

namespace TestMonitor\Custify\Actions;

use TestMonitor\Custify\Resources\CustomData;
use TestMonitor\Custify\Resources\Event;
use TestMonitor\Custify\Transforms\TransformsEvents;
use TestMonitor\Custify\Validator;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Transforms\TransformsPeople;
use TestMonitor\Custify\Exceptions\NotFoundException;

trait ManagesEvents
{
    use TransformsEvents;

    /**
     * Create or update a person.
     *
     * @param \TestMonitor\Custify\Resources\Event $event
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     * @return \TestMonitor\Custify\Resources\Person
     */
    public function createEvent(Event $event)
    {
        $response = $this->post('event', ['json' => $this->toCustifyEvent($event)]);

        return $response;
    }
}
