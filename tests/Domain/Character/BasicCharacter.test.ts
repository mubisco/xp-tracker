import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterId } from '@/Domain/Character/CharacterId'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { ExperienceDto } from '@/Domain/Character/ExperienceDto'
import { Health } from '@/Domain/Character/Health'
import { HitPointsDto } from '@/Domain/Character/HitPointsDto'
import { beforeEach, describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  let sut: BasicCharacter

  beforeEach(() => {
    sut = BasicCharacter.fromValues(CharacterName.fromString('Darling'), Experience.fromXp(345), Health.fromMaxHp(25))
  })
  test('It should return proper CharacterId', () => {
    const characterId = sut.id()
    expect(characterId).toBeInstanceOf(CharacterId)
    const againCharacterId = sut.id()
    expect(characterId.equals(againCharacterId))
  })
  test('It should return proper Name', () => {
    expect(sut.name()).toBeInstanceOf(CharacterName)
    expect(sut.name().value()).equals('Darling')
  })
  test('It should return proper experience data', () => {
    const values = sut.experience()
    expect(values).toBeInstanceOf(ExperienceDto)
    expect(values.actual).equals(345)
    expect(values.nextLevel).equals(900)
    expect(values.level).equals(2)
  })
  test('It should return proper hitpoints', () => {
    const hitpoints = sut.hitPoints()
    expect(hitpoints).toBeInstanceOf(HitPointsDto)
    expect(hitpoints.max).equals(25)
    expect(hitpoints.current).equals(25)
  })
})
