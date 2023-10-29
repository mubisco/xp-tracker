import { EncounterLevelTag } from './EncounterLevelTag'
const TRESHOLDS = [
  [0, 0, 0, 0],
  [25, 50, 75, 100],
  [50, 100, 150, 200],
  [75, 150, 225, 400],
  [125, 250, 375, 500],
  [250, 500, 750, 1100],
  [300, 600, 900, 1400],
  [350, 750, 1100, 1700],
  [450, 900, 1400, 2100],
  [550, 1100, 1600, 2400],
  [600, 1200, 1900, 2800],
  [800, 1600, 2400, 3600],
  [1000, 2000, 3000, 4500],
  [1100, 2200, 3400, 5100],
  [1250, 2500, 3800, 5700],
  [1400, 2800, 4300, 6400],
  [1600, 3200, 4800, 7200],
  [2000, 3900, 5900, 8800],
  [2100, 4200, 6300, 9500],
  [2400, 4900, 7300, 10900],
  [2800, 5700, 8500, 12700]
]

export class EncounterLevel {
  static fromValues (characterLevels: number[], monsterLevels: number[]): EncounterLevel {
    return new this(characterLevels, monsterLevels)
  }
  // eslint-disable-next-line
  private constructor (
    private readonly characterLevels: number[],
    private readonly monsterLevels: number[]
  ) {}

  value (): EncounterLevelTag {
    if (this.characterLevels.length === 0) {
      return EncounterLevelTag.UNASSIGNED
    }
    const monsterXpSum = this.monsterLevels.reduce((accumulator, currentValue) => {
      return accumulator + currentValue
    }, 0)
    const modifiedXpTotal = monsterXpSum * this.multiplier()
    const tresholds = this.getTresholds()
    for (let i = 0; i < tresholds.length - 1; i++) {
      const currentTreshold = tresholds[i]
      if (modifiedXpTotal <= currentTreshold) {
        return this.levelByIndex(i)
      }
    }
    return EncounterLevelTag.DEADLY
  }

  private getTresholds (): number[] {
    const baseTresholds = [0, 0, 0, 0]
    this.characterLevels.forEach(characterLevel => {
      const characterTresholds = TRESHOLDS[characterLevel]
      baseTresholds[0] += characterTresholds[0]
      baseTresholds[1] += characterTresholds[1]
      baseTresholds[2] += characterTresholds[2]
      baseTresholds[3] += characterTresholds[3]
    })
    return baseTresholds
  }

  private levelByIndex (index: number): EncounterLevelTag {
    if (index === 0) {
      return EncounterLevelTag.EASY
    }
    if (index === 1) {
      return EncounterLevelTag.MEDIUM
    }
    if (index === 2) {
      return EncounterLevelTag.HARD
    }
    return EncounterLevelTag.DEADLY
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
