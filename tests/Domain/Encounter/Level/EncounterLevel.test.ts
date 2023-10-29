import { EncounterLevel } from '@/Domain/Encounter/Level/EncounterLevel'
import { EncounterLevelTag } from '@/Domain/Encounter/Level/EncounterLevelTag'
import { describe, expect, test } from 'vitest'

describe('Testing EncounterLevel', () => {
  const encounterData = [
    { characterLevels: [], monsterXp: [], expectedResult: EncounterLevelTag.UNASSIGNED },
    { characterLevels: [1], monsterXp: [25], expectedResult: EncounterLevelTag.EASY },
    { characterLevels: [1], monsterXp: [50], expectedResult: EncounterLevelTag.MEDIUM },
    { characterLevels: [1], monsterXp: [75], expectedResult: EncounterLevelTag.HARD },
    { characterLevels: [1], monsterXp: [100], expectedResult: EncounterLevelTag.DEADLY },
    { characterLevels: [1, 1], monsterXp: [100], expectedResult: EncounterLevelTag.MEDIUM },
    { characterLevels: [1, 2], monsterXp: [75], expectedResult: EncounterLevelTag.EASY },
    { characterLevels: [1], monsterXp: [25, 25], expectedResult: EncounterLevelTag.HARD },
    { characterLevels: [1, 2], monsterXp: [100], expectedResult: EncounterLevelTag.EASY },
    { characterLevels: [1, 2], monsterXp: [25, 25, 25], expectedResult: EncounterLevelTag.MEDIUM }
  ]
  test.each(encounterData)('It should return proper values', (rawData) => {
    const sut = EncounterLevel.fromValues(rawData.characterLevels, rawData.monsterXp)
    expect(sut.value()).toBe(rawData.expectedResult)
  })
})
