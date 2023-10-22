import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { EventListener } from '@/Domain/Shared/Event/EventListener'
import { EventSubscriber } from '@/Domain/Shared/Event/EventSubscriber'

export class VueEventBus implements EventBus, EventSubscriber {
  private _listeners: EventListener[]

  constructor () {
    this._listeners = []
  }

  subscribe (listener: EventListener): void {
    this._listeners.push(listener)
  }

  async publish (events: DomainEvent[]): Promise<void> {
    this._listeners.forEach((listener: EventListener): void => this.checkMatchingEvents(listener, events))
    return Promise.resolve()
  }

  private checkMatchingEvents (listener: EventListener, events: DomainEvent[]): void {
    events.forEach((event: DomainEvent): void => {
      if (listener.listensTo(event.name())) {
        listener.handle(event)
      }
    })
  }
}
