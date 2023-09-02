export class HitPointsDto {
  public readonly max: number
  public readonly current: number

  constructor (max: number, current: number) {
    this.max = max
    this.current = current
  }
}
