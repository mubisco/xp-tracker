import { HitPointsDto } from './HitPointsDto'

export class Health {
  private _maxHp: number
  private _currentHp: number

  static fromMaxHp (maxHp: number) {
    return new this(maxHp, maxHp)
  }

  static fromValues (maxHp: number, currentHp: number) {
    return new this(maxHp, currentHp)
  }

  private constructor (maxHp: number, currentHp: number) {
    if (maxHp < 0) {
      throw new RangeError('Max hitpoints should be 0 or greater!')
    }
    this._maxHp = maxHp
    this._currentHp = currentHp
  }

  hitpoints (): HitPointsDto {
    return new HitPointsDto(this._maxHp, this._currentHp)
  }
}
