import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'
import { EventBus } from '@/Domain/Shared/Event/EventBus'

export class InMemoryEventBusSpy implements EventBus {
  public events: DomainEvent[] = []

  publish (events: DomainEvent[]): void {
    const updatedEvents = [...this.events, ...events]
    this.events = updatedEvents
  }
}
