export class EncounterName {
  static fromString (value: string): EncounterName {
    return new EncounterName(value)
  }

  // eslint-disable-next-line
  private constructor (private readonly name: string) {
    if (!name) {
      throw new RangeError('EncounterName must be not empty!!!')
    }
  }

  value (): string {
    return this.name
  }
}
