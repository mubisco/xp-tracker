import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { DummyCharacterVisitor } from './DummyCharacterVisitor'
import { Experience } from '@/Domain/Character/Experience'
import { ExperienceDto } from '@/Domain/Character/ExperienceDto'
import { Health } from '@/Domain/Character/Health'
import { HitPointsDto } from '@/Domain/Character/HitPointsDto'
import { beforeEach, describe, test, expect } from 'vitest'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

describe('Testing BasicCharacter', () => {
  let sut: BasicCharacter

  beforeEach(() => {
    sut = BasicCharacter.fromValues(CharacterName.fromString('Darling'), Experience.fromXp(345), Health.fromMaxHp(25))
  })
  test('It should return proper CharacterId', () => {
    const characterId = sut.id()
    expect(characterId).toBeInstanceOf(Ulid)
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
  test('It should return proper visitor result', () => {
    const result = sut.visit(new DummyCharacterVisitor())
    expect(result).equals(sut.id().value())
  })
})
