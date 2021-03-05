<?php

namespace TestMonitor\Custify\Actions;

use TestMonitor\Custify\Resources\Event;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Resources\Resource;
use TestMonitor\Custify\Transforms\TransformsEvents;

trait ManagesEvents
{
    use TransformsEvents;

    /**
     * Create an event.
     *
     * @param \TestMonitor\Custify\Resources\Event $event
     *
     * @throws \TestMonitor\Custify\Exceptions\InvalidDataException
     */
    public function createEvent(Event $event)
    {
        $this->post('event', ['json' => $this->toCustifyEvent($event)]);

        return $event;
    }

    /**
     * Fire an event.
     *
     * @param string $name
     * @param $resource
     * @param array $metadata
     *
     * @return mixed
     */
    public function event(string $name, Resource $resource, array $metadata = [])
    {
        $data = [
            'created_at' => (new \DateTime())->format('Y-m-d h:i:s'),
            'name' => $name,
            'metadata' => (object) $metadata,
        ];

        if ($resource instanceof Company) {
            $data['company_id'] = $resource->company_id;
        }

        if ($resource instanceof Person && $resource->user_id) {
            $data['user_id'] = $resource->user_id;
        }

        if ($resource instanceof Person && $resource->email) {
            $data['email'] = $resource->email;
        }

        $event = new Event($data);

        $this->post('event', ['json' => $this->toCustifyEvent($event)]);

        return $event;
    }
}
