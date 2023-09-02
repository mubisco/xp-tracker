import { isValid, ulid } from 'ulidx'

export class CharacterId {
  private _ulid: string

  public static fromString (ulid: string): CharacterId {
    return new CharacterId(ulid)
  }

  public static fromEmpty (): CharacterId {
    return new CharacterId(ulid())
  }

  constructor (ulid: string) {
    if (!isValid(ulid)) {
      throw new RangeError(`Ulid value ${ulid} not valid!!!`)
    }
    this._ulid = ulid
  }

  value (): string {
    return this._ulid
  }
}
