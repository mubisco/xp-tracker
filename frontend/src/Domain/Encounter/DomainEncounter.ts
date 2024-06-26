import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Encounter } from './Encounter'
import { EncounterName } from './EncounterName'
import { EncounterVisitor } from './EncounterVisitor'
import { EncounterMonster } from './Monster/EncounterMonster'
import { MonsterNotFoundError } from './MonsterNotFoundError'
import { EncounterStatus } from './EncounterStatus'
import { DomainEvent } from '../Shared/Event/DomainEvent'
import { EncounterWasFinished } from './EncounterWasFinished'
import { EncounterLevelTag } from './Level/EncounterLevelTag'
import { PartyTresholdDto } from './Party/PartyTresholdDto'
import { EncounterLevel } from './Level/EncounterLevel'

export class DomainEncounter implements Encounter {
  private ulid: Ulid
  private _name: EncounterName
  private _encounterMonsters: EncounterMonster[]
  private _status: EncounterStatus
  private _events: DomainEvent[]
  private _characterLevels: number[]

  static withName (name: EncounterName): DomainEncounter {
    return new DomainEncounter(name.value())
  }

  private constructor (name: string) {
    this.ulid = Ulid.fromEmpty()
    this._name = EncounterName.fromString(name)
    this._encounterMonsters = []
    this._status = EncounterStatus.OPEN
    this._events = []
    this._characterLevels = []
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
      throw new MonsterNotFoundError(`${monster.name()} not in this encounter`)
    }
    this._encounterMonsters = updatedMonsters
  }

  monsters (): EncounterMonster[] {
    return this._encounterMonsters
  }

  updateLevel (tresholds: PartyTresholdDto): void {
    this._characterLevels = tresholds.characterLevels
  }

  private monsterXpValues (): number[] {
    const values: number[] = []
    this._encounterMonsters.forEach((currentMonster) => {
      values.push(currentMonster.xp())
    })
    return values
  }

  level (): EncounterLevelTag {
    if (this._encounterMonsters.length === 0) {
      return EncounterLevelTag.EMPTY
    }
    const level = EncounterLevel.fromValues(this._characterLevels, this.monsterXpValues())
    return level.value()
  }
}
