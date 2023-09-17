import { isValid, ulid } from 'ulidx'

export class Ulid {
  private _ulid: string

  static fromString (ulid: string): Ulid {
    return new this(ulid)
  }

  static fromEmpty (): Ulid {
    return new this(ulid())
  }

  public constructor (ulid: string) {
    if (!isValid(ulid)) {
      throw new RangeError(`Ulid value ${ulid} not valid!!!`)
    }
    this._ulid = ulid
  }

  value (): string {
    return this._ulid
  }

  equals (another: Ulid): boolean {
    return another.constructor === this.constructor && another.value() === this._ulid
  }
}
