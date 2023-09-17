import { HitPointsDto } from './HitPointsDto'

export class Health {
  static fromMaxHp (maxHp: number) {
    return new this(maxHp, maxHp)
  }

  static fromValues (maxHp: number, currentHp: number) {
    return new this(maxHp, currentHp)
  }

  private constructor (private readonly maxHp: number, private readonly currentHp: number) {
    if (this.maxHp < 0) {
      throw new RangeError('Max hitpoints should be 0 or greater!')
    }
  }

  hitpoints (): HitPointsDto {
    return new HitPointsDto(this.maxHp, this.currentHp)
  }
}
