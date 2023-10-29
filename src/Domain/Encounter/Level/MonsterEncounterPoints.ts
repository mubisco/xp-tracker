export class MonsterEncounterPoints {
  static fromXpValues (monsterLevels: number[]): MonsterEncounterPoints {
    return new this(monsterLevels)
  }
  // eslint-disable-next-line
  private constructor (
    private readonly monsterLevels: number[]
  ) {}

  public value (): number {
    const monsterXpSum = this.monsterLevels.reduce((accumulator, currentValue) => {
      return accumulator + currentValue
    }, 0)
    return Math.floor(monsterXpSum * this.multiplier())
  }

  private multiplier (): number {
    const monsterQuantity = this.monsterLevels.length
    if (monsterQuantity > 14) {
      return 4
    }
    if (monsterQuantity > 10) {
      return 3
    }
    if (monsterQuantity > 6) {
      return 2.5
    }
    if (monsterQuantity > 2) {
      return 2
    }
    if (monsterQuantity > 1) {
      return 1.5
    }
    return 1
  }
}
