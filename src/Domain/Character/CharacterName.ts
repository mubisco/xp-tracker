export class CharacterName {
  private _value: string

  static fromString (name: string): CharacterName {
    return new this(name)
  }

  private constructor (name: string) {
    if (name.length === 0) {
      throw new RangeError('Name must be not empty')
    }
    this._value = name
  }

  value (): string {
    return this._value
  }
}
