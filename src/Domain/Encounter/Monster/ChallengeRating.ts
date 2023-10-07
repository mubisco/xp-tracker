export class ChallengeRating {
  static fromString (value: string): ChallengeRating {
    return new ChallengeRating(value)
  }
  // eslint-disable-next-line
  private constructor (
    private readonly _value: string
  ) {
    this.validateValue()
  }

  value (): string {
    return this._value
  }

  private validateValue (): void {
    const validFractions = ['1/8', '1/4', '1/2']
    if (validFractions.indexOf(this._value) !== -1) {
      return
    }
    if (this._value.indexOf('/') !== -1) {
      throw new RangeError(`${this._value} is not a valid CR fraction value`)
    }
    const integerValue = parseInt(this._value, 10)
    if (integerValue < 0 || integerValue > 30) {
      throw new RangeError(`${this._value} is not a valid CR value`)
    }
  }
}
