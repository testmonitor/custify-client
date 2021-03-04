<?php

namespace TestMonitor\Custify\Transforms;

use TestMonitor\Custify\Resources\Event;

trait TransformsEvents
{
    /**
     * @param Event $event
     *
     * @return array
     */
    protected function toCustifyEvent(Event $event): array
    {
        return [
            'name' => $event->name,
            'created_at' => $event->created_at,
            'user_id' => $event->user_id,
            'email' => $event->email,
            'company_id' => $event->company_id,

            'metadata' => $event->metadata ?? [],
        ];
    }
}
