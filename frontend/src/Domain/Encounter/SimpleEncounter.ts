import { Ulid } from '../Shared/Identity/Ulid'
import { EncounterName } from './EncounterName'

export class SimpleEncounter {
  private readonly partyUlid: Ulid
  private readonly partyName: EncounterName

  static fromName (name: string): SimpleEncounter {
    const ulid = Ulid.fromEmpty()
    return new this(ulid.value(), name)
  }

  static fromValues (ulid: string, name: string): SimpleEncounter {
    return new this(ulid, name)
  }

  private constructor (ulid: string, name: string) {
    this.partyUlid = Ulid.fromString(ulid)
    this.partyName = EncounterName.fromString(name)
  }

  ulid (): string {
    return this.partyUlid.value()
  }

  name (): string {
    return this.partyName.value()
  }
}
