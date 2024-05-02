import { Ulid } from "../Shared/Identity/Ulid"

export class Party {
  static fromValues(ulid: string, name: string): Party {
    return new Party(ulid, name)
  }

  static fromName(name: string): Party {
    const ulid = Ulid.fromEmpty();
    return new Party(ulid.value(), name)
  }

  private readonly _ulid: Ulid

  // eslint-disable-next-line
  constructor (ulid: string, private readonly partyName: string) {
    this._ulid = Ulid.fromString(ulid)
    if (this.partyName === '') {
      throw new RangeError('Party name cannot be empty')
    }
  }
  id(): string {
    return this._ulid.value()
  }
  name(): string {
    return this.partyName
  }
}

