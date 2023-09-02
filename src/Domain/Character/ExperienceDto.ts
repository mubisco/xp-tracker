export class ExperienceDto {
  public actual: number
  public level: number
  public nextLevel: number

  constructor (actual: number, level: number, nextLevel: number) {
    this.actual = actual
    this.level = level
    this.nextLevel = nextLevel
  }
}
