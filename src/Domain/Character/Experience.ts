import { ExperienceDto } from './ExperienceDto'

const TRESHOLDS = [
  0,
  300,
  900,
  2700,
  6500,
  14000,
  23000,
  34000,
  48000,
  64000,
  85000,
  100000,
  120000,
  140000,
  165000,
  195000,
  225000,
  265000,
  305000,
  355000
]
export class Experience {
  private _actualXp: number

  static fromXp (xp: number): Experience {
    return new this(xp)
  }

  private constructor (actualXp: number) {
    if (actualXp < 0) {
      throw new RangeError(`Actual XP should be 0 or more, ${actualXp} given!!!`)
    }
    this._actualXp = actualXp
  }

  values (): ExperienceDto {
    return new ExperienceDto(this._actualXp, this.level(), this.nextLevelXp())
  }

  private nextLevelXp (): number {
    for (let i = 0; i < TRESHOLDS.length; i++) {
      if (this._actualXp < TRESHOLDS[i]) {
        return TRESHOLDS[i]
      }
    }
    // TODO: Revisar este -1
    return -1
  }

  private level (): number {
    for (let i = 0; i < TRESHOLDS.length; i++) {
      if (this._actualXp < TRESHOLDS[i]) {
        return i
      }
    }
    return 20
  }

  add (another: Experience): Experience {
    const anotherValues = another.values()
    return new Experience(this._actualXp + anotherValues.actual)
  }
}
