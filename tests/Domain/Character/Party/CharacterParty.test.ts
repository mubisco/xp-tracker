import { CharacterParty } from '@/Domain/Character/Party/CharacterParty'
import { describe, expect, test } from 'vitest'
import { BasicCharacterOM } from '../BasicCharacterOM'
import { Experience } from '@/Domain/Character/Experience'

describe('Testing CharacterParty', () => {
  test('It should be of proper class', () => {
    const sut = new CharacterParty([])
    expect(sut).toBeInstanceOf(CharacterParty)
  })
  test('It should return zero count when party empty', () => {
    const sut = new CharacterParty([])
    expect(sut.count()).toBe(0)
  })
  test('It should return number of characters', () => {
    const sut = new CharacterParty([
      BasicCharacterOM.withActualHp(25),
      BasicCharacterOM.withActualHp(25)
    ])
    expect(sut.count()).toBe(2)
  })
  test('It should apply all experience points when single character', () => {
    const characterOne = BasicCharacterOM.withActualHp(25)
    const sut = new CharacterParty([characterOne])
    const experience = Experience.fromXp(200)
    sut.updateExperience(experience)
    const characterExperience = characterOne.experience()
    expect(characterExperience.actual).toBe(545)
  })
  test('It should split equally all experience between characters', () => {
    const characterOne = BasicCharacterOM.withActualHp(25)
    const characterTwo = BasicCharacterOM.withActualHp(25)
    const sut = new CharacterParty([characterOne, characterTwo])
    const experience = Experience.fromXp(225)
    sut.updateExperience(experience)
    const characterOneExperience = characterOne.experience()
    expect(characterOneExperience.actual).toBe(457)
    const characterTwoExperience = characterOne.experience()
    expect(characterTwoExperience.actual).toBe(457)
  })
})
