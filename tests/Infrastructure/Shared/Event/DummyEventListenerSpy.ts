import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'
import { EventListener } from '@/Domain/Shared/Event/EventListener'

export class DummyEventListenerSpy implements EventListener {
  public _timesCalled: number

  // eslint-disable-next-line
  constructor (private readonly eventName: string) {
    this._timesCalled = 0
  }

  handle (event: DomainEvent): Promise<any> {
    this._timesCalled++
    return Promise.resolve()
  }

  listensTo (eventName: string): boolean {
    return eventName === this.eventName
  }
}
