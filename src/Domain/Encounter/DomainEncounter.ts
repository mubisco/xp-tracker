import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Encounter } from './Encounter'
import { EncounterName } from './EncounterName'
import { EncounterVisitor } from './EncounterVisitor'
import { EncounterMonster } from './Monster/EncounterMonster'
import { MonsterNotFoundError } from './MonsterNotFoundError'
import { EncounterStatus } from './EncounterStatus'
import { DomainEvent } from '../Shared/Event/DomainEvent'
import { EncounterWasFinished } from './EncounterWasFinished'

export class DomainEncounter implements Encounter {
  private ulid: Ulid
  private _name: EncounterName
  private _encounterMonsters: EncounterMonster[]
  private _status: EncounterStatus
  private _events: DomainEvent[]

  static withName (name: EncounterName): DomainEncounter {
    return new DomainEncounter(name.value())
  }

  private constructor (name: string) {
    this.ulid = Ulid.fromEmpty()
    this._name = EncounterName.fromString(name)
    this._encounterMonsters = []
    this._status = EncounterStatus.OPEN
    this._events = []
  }

  pullEvents (): DomainEvent[] {
    const events = [...this._events]
    this._events = []
    return events
  }

  finish (): void {
    this._status = EncounterStatus.DONE
    this._events.push(new EncounterWasFinished(this.ulid.value(), this.totalXp()))
  }

  status (): EncounterStatus {
    return this._status
  }

  private totalXp (): number {
    let totalXp = 0
    this._encounterMonsters.forEach((monster): void => {
      totalXp += monster.xp()
    })
    return totalXp
  }

  id (): Ulid {
    return this.ulid
  }

  name (): EncounterName {
    return this._name
  }

  visit (visitor: EncounterVisitor<any>) {
    return visitor.visitDomainEncounter(this)
  }

  addMonster (monster: EncounterMonster): void {
    this._encounterMonsters.push(monster)
  }

  removeMonster (monster: EncounterMonster): void {
    const updatedMonsters = this._encounterMonsters.filter((currenMonster) => !currenMonster.equalsTo(monster))
    if (this._encounterMonsters.length === updatedMonsters.length) {
      throw new MonsterNotFoundError('Method not implemented.')
    }
    this._encounterMonsters = updatedMonsters
  }

  monsters (): EncounterMonster[] {
    return this._encounterMonsters
  }
}
