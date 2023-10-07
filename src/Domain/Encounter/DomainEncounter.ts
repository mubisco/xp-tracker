import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Encounter } from './Encounter'
import { EncounterName } from './EncounterName'
import { EncounterVisitor } from './EncounterVisitor'
import { EncounterMonster } from './Monster/EncounterMonster'

export class DomainEncounter implements Encounter {
  private ulid: Ulid
  private _name: EncounterName
  private _encounterMonsters: EncounterMonster[]

  static withName (name: EncounterName): DomainEncounter {
    return new DomainEncounter(name.value())
  }

  private constructor (name: string) {
    this.ulid = Ulid.fromEmpty()
    this._name = EncounterName.fromString(name)
    this._encounterMonsters = []
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

  monsters (): EncounterMonster[] {
    return this._encounterMonsters
  }
}
