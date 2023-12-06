import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterStatus } from '@/Domain/Encounter/EncounterStatus'
import { EncounterLevelTag } from '@/Domain/Encounter/Level/EncounterLevelTag'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'
import { describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterFactory', () => {
  test('It should return proper basic Domain Encounter', () => {
    const sut = new LocalStorageEncounterFactory()
    const receivedResult = '{"ulid":"01HDXRYRYTN9S5RVEY1ZYZQZ7Y","name":"Pollos","status":"OPEN","level":"UNASSIGNED","characterLevels":[],"monsters":[]}'
    const result = sut.make(receivedResult)
    expect(result).toBeInstanceOf(DomainEncounter)
    expect(result.monsters()).toHaveLength(0)
    expect(result.id().value()).toBe('01HDXRYRYTN9S5RVEY1ZYZQZ7Y')
    expect(result.name().value()).toBe('Pollos')
    expect(result.status()).toBe(EncounterStatus.OPEN)
    expect(result.level()).toBe(EncounterLevelTag.UNASSIGNED)
  })

  test('It should return proper basic Domain Encounter with monster', () => {
    const sut = new LocalStorageEncounterFactory()
    const receivedResult = '{"ulid":"01HDXRZ7JGRXD9X8XF0WE143NF","name":"Pollos","status":"OPEN","level":"UNASSIGNED","characterLevels":[],"monsters":[{"name":"Some name","xp":2500,"cr":"1/2"}]}'
    const result = sut.make(receivedResult)
    expect(result.id().value()).toBe('01HDXRZ7JGRXD9X8XF0WE143NF')
    const monsters = result.monsters()
    expect(monsters).toHaveLength(1)
    expect(monsters[0].name()).toBe('Some name')
    expect(monsters[0].xp()).toStrictEqual(2500)
  })

  test('It should return proper basic Domain Encounter with players assigned', () => {
    const sut = new LocalStorageEncounterFactory()
    const receivedResult = '{"ulid":"01HDXRZFEA3K133FRY82BQ5HKR","name":"Pollos","status":"OPEN","level":"DEADLY","characterLevels":[2,2],"monsters":[{"name":"Some name","xp":2500,"cr":"1/2"}]}'
    const result = sut.make(receivedResult)
    expect(result.id().value()).toBe('01HDXRZFEA3K133FRY82BQ5HKR')
    expect(result.level()).toBe(EncounterLevelTag.DEADLY)
  })
})
