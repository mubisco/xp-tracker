import { isValid, ulid } from 'ulidx'

export abstract class AbstractUlid {
  private _ulid: string

  static fromString<T extends AbstractUlid> (this: { new(ulid: string): T }, ulid: string): T {
    return new this(ulid)
  }

  static fromEmpty<T extends AbstractUlid> (this: { new(ulid: string): T }): T {
    return new this(ulid())
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

