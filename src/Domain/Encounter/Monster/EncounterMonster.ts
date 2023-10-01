export class EncounterMonster {
  // eslint-disable-next-line
  constructor (
    private readonly _name: string,
    private readonly _xp: number,
    private readonly _challengeRating: string
  ) {
    if (this._name === '') {
      throw new RangeError()
    }
    if (this._xp < 1) {
      throw new RangeError()
    }
  }

  name (): string {
    return this._name
  }

  xp (): number {
    return this._xp
  }

  challengeRating (): string {
    return this._challengeRating
  }
}
