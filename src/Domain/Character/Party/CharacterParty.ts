import { Character } from '@/Domain/Character/Character'
import { Experience } from '@/Domain/Character/Experience'
import { Party } from '@/Domain/Character/Party/Party'
import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'
import { PartyWasUpdated } from './PartyWasUpdated'

export class CharacterParty implements Party {
  private _events: DomainEvent[]
  // eslint-disable-next-line
  constructor (private readonly members: Character[]) {
    this._events = []
  }

  pullEvents (): DomainEvent[] {
    const events = [...this._events]
    this._events = []
    return events
  }

  updateExperience (experiencePoints: Experience): void {
    const experienceToAdd = experiencePoints.split(this.count())
    this.members.forEach((character: Character): void => {
      character.addExperience(experienceToAdd)
    })
    this._events.push(new PartyWasUpdated())
  }

  count (): number {
    return this.members.length
  }
}
