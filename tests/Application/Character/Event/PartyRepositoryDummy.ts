import { Experience } from '@/Domain/Character/Experience'
import { Party } from '@/Domain/Character/Party/Party'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'
import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'
import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'

class PartyDummySpy implements Party {
  public experiencePoints: number = 0
  private _events: DomainEvent[] = []
  // eslint-disable-next-line
  updateExperience (experiencePoints: Experience): void {
    this.experiencePoints = experiencePoints.values().actual
    this._events.push(new PartyWasUpdated())
  }

  pullEvents (): DomainEvent[] {
    return this._events
  }
}

export class PartyRepositoryDummy implements PartyRepository {
  find (): Promise<Party> {
    return Promise.resolve(new PartyDummySpy())
  }
}
