import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'

export class EncounterWasFinished implements DomainEvent {
  private _occurredOn: Date

  constructor (private readonly encounterId: string, private readonly totalXp: number) {
    this._occurredOn = new Date()
  }

  payload (): { [key: string]: any } {
    return { encounterId: this.encounterId, totalXp: this.totalXp }
  }

  occurredOn (): Date {
    return this._occurredOn
  }

  name (): string {
    return 'EncounterWasFinished'
  }
}
