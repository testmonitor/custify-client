<?php

namespace TestMonitor\Custify\Actions;

use TestMonitor\Custify\Resources\Event;
use TestMonitor\Custify\Transforms\TransformsEvents;

trait ManagesEvents
{
    use TransformsEvents;

    /**
     * Insert an event.
     *
     * @param \TestMonitor\Custify\Resources\Event $event
     *
     * @return bool
     */
    public function insertEvent(Event $event): bool
    {
        $response = $this->post('event', ['json' => $this->toCustifyEvent($event)]);

        return empty(json_decode($response, JSON_FORCE_OBJECT));
    }
}
