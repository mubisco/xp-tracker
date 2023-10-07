import { ChallengeRating } from './ChallengeRating'

export class EncounterMonster {
  private readonly _challengeRating: ChallengeRating

  static fromValues (name: string, xp: number, challengeRating: string): EncounterMonster {
    return new EncounterMonster(name, xp, challengeRating)
  }
  // eslint-disable-next-line
  private constructor (
    private readonly _name: string,
    private readonly _xp: number,
    cr: string
  ) {
    if (this._name === '') {
      throw new RangeError()
    }
    if (this._xp < 1) {
      throw new RangeError()
    }
    this._challengeRating = ChallengeRating.fromString(cr)
  }

  name (): string {
    return this._name
  }

  xp (): number {
    return this._xp
  }

  challengeRating (): string {
    return this._challengeRating.value()
  }
}
