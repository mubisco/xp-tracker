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
  static fromXp (xp: number): Experience {
    return new this(xp)
  }

  private readonly actualXp: number

  private constructor (actualXp: number) {
    // @ts-ignore
    this.actualXp = parseInt(actualXp, 10)
    if (this.actualXp < 0) {
      throw new RangeError(`Actual XP should be 0 or more, ${this.actualXp} given!!!`)
    }
  }

  values (): ExperienceDto {
    return new ExperienceDto(this.actualXp, this.level(), this.nextLevelXp())
  }

  private nextLevelXp (): number {
    for (let i = 0; i < TRESHOLDS.length; i++) {
      if (this.actualXp < TRESHOLDS[i]) {
        return TRESHOLDS[i]
      }
    }
    // TODO: Revisar este -1
    return -1
  }

  private level (): number {
    for (let i = 0; i < TRESHOLDS.length; i++) {
      if (this.actualXp < TRESHOLDS[i]) {
        return i
      }
    }
    return 20
  }

  add (another: Experience): Experience {
    const anotherValues = another.values()
    return new Experience(this.actualXp + anotherValues.actual)
  }

  split (quantity: number): Experience {
    const finalValue = Math.floor(this.actualXp / quantity)
    return Experience.fromXp(finalValue)
  }
}
