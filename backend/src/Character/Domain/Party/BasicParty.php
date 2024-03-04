<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain\Party;

use XpTracker\Shared\Domain\AggregateRoot;

final class BasicParty extends AggregateRoot implements Party
{
    private string $name;

    public static function create(string $id, string $name): static
    {
        $instance = new static($id);
        $event = new PartyWasCreated($id, $name);
        $instance->apply($event);
        return $instance;
    }

    public function toJson(): string
    {
        $data = ['name' => $this->name];
        return json_encode($data);
    }

    protected function applyPartyWasCreated(PartyWasCreated $event): void
    {
        $this->name = $event->partyName;
    }
}
