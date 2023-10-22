import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'
import { EventBus } from '@/Domain/Shared/Event/EventBus'

export class InMemoryEventBusSpy implements EventBus {
  public events: DomainEvent[] = []

  async publish (events: DomainEvent[]): Promise<void> {
    const updatedEvents = [...this.events, ...events]
    this.events = updatedEvents
    return Promise.resolve()
  }
}
