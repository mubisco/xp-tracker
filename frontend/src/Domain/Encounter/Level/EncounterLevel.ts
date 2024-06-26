import { EncounterLevelTag } from './EncounterLevelTag'
import { MonsterEncounterPoints } from './MonsterEncounterPoints'
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

const LEVEL_BY_INDEX = [
  EncounterLevelTag.EASY,
  EncounterLevelTag.MEDIUM,
  EncounterLevelTag.HARD,
  EncounterLevelTag.DEADLY
]

export class EncounterLevel {
  static empty (): EncounterLevel {
    return new this([], [])
  }

  static fromValues (characterLevels: number[], monsterXpValues: number[]): EncounterLevel {
    return new this(characterLevels, monsterXpValues)
  }

  private readonly monsterEncounterPoints: MonsterEncounterPoints
  private readonly totalCharacters: number
  // eslint-disable-next-line
  private constructor (
    private readonly characterLevels: number[],
    monsterXpValues: number[]
  ) {
    this.monsterEncounterPoints = MonsterEncounterPoints.fromXpValues(monsterXpValues)
    this.totalCharacters = characterLevels.length
  }

  value (): EncounterLevelTag {
    if (this.totalCharacters === 0) {
      return EncounterLevelTag.UNASSIGNED
    }
    const tresholdIndex = this.calculateIndex()
    return LEVEL_BY_INDEX[tresholdIndex]
  }

  private calculateIndex (): number {
    const monsterPoints = this.monsterEncounterPoints.value()
    const tresholds = this.sumTresholds()
    let tresholdIndex = 0
    for (let i = 0; i < tresholds.length; i++) {
      const currentTreshold = tresholds[i]
      if (monsterPoints < currentTreshold) {
        continue
      }
      tresholdIndex = i
    }
    return tresholdIndex
  }

  private sumTresholds (): number[] {
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
}
