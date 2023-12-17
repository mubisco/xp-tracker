export class CharacterName {
  static fromString (name: string): CharacterName {
    return new this(name)
  }

  private constructor (private readonly _value: string) {
    if (this._value.length === 0) {
      throw new RangeError('Name must be not empty')
    }
  }

  value (): string {
    return this._value
  }
}
