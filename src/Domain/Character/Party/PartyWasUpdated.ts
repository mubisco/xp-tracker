import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'

export class PartyWasUpdated implements DomainEvent {
  private _occurredOn: Date

  constructor () {
    this._occurredOn = new Date()
  }

  occurredOn (): Date {
    return this._occurredOn
  }

  name (): string {
    return 'PartyWasUpdated'
  }

  payload (): { [key: string]: any; } {
    return {}
  }
}
