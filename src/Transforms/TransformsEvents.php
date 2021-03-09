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
        return array_filter([
            'name' => $event->name,
            'created_at' => $event->created_at,
            'email' => $event->person->email ?? null,
            'user_id' => $event->person->user_id ?? null,
            'company_id' => $event->company->company_id ?? null,

            'metadata' => $event->metadata->toObject(),
        ]);
    }
}
