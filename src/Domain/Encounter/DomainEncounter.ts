import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Encounter } from './Encounter'
import { EncounterName } from './EncounterName'

export class DomainEncounter implements Encounter {
  private ulid: Ulid
  private _name: EncounterName

  static withName (name: EncounterName): DomainEncounter {
    return new DomainEncounter(name.value())
  }

  private constructor (name: string) {
    this.ulid = Ulid.fromEmpty()
    this._name = EncounterName.fromString(name)
  }

  id (): Ulid {
    return this.ulid
  }

  name (): EncounterName {
    return this._name
  }
}
