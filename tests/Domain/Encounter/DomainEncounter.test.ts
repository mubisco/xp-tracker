import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { describe, expect, test } from 'vitest'
import { DomainEncounterOM } from './DomainEncounterOM'
import { SimpleStringEncounterVisitor } from './SimpleStringEncounterVisitor'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { MonsterNotFoundError } from '@/Domain/Encounter/MonsterNotFoundError'
import { EncounterStatus } from '@/Domain/Encounter/EncounterStatus'
import { EncounterLevel } from '@/Domain/Encounter/EncounterLevel'
import { PartyTresholdDto } from '@/Domain/Encounter/Party/PartyTresholdDto'

describe('Testing DomainEncounter', () => {
  const visitor = new SimpleStringEncounterVisitor()

  test('It should return proper values', () => {
    const sut = DomainEncounterOM.withName('pollos')
    expect(sut.id()).toBeInstanceOf(Ulid)
    expect(sut.name()).toBeInstanceOf(EncounterName)
    expect(sut.name().value()).toBe('pollos')
    expect(sut.monsters()).toHaveLength(0)
    expect(sut.status()).toBe(EncounterStatus.OPEN)
    expect(sut.level()).toBe(EncounterLevel.UNASSIGNED)
    expect(sut.pullEvents()).toHaveLength(0)
  })

  test('It should add a monster properly', () => {
    const monsterToAdd = EncounterMonster.fromValues('Pollo Papi', 2500, '1/2')
    const sut = DomainEncounterOM.withName('pollos')
    sut.addMonster(monsterToAdd)
    const monsters = sut.monsters()
    expect(monsters).toHaveLength(1)
    expect(monsters[0]).toStrictEqual(monsterToAdd)
  })

  test('It should throw error when trying to remove a non-existant monster', () => {
    const monsterToRemove = EncounterMonster.fromValues('Pollo Papi', 2500, '1/2')
    const sut = DomainEncounterOM.withName('pollos')
    expect(() => {
      sut.removeMonster(monsterToRemove)
    }).toThrow(MonsterNotFoundError)
  })

  test('It should remove properly a monster', () => {
    const monsterToAdd = EncounterMonster.fromValues('Pollo Papi', 2500, '1/2')
    const monsterToRemove = EncounterMonster.fromValues('Pollo Papi', 2500, '1/2')
    const sut = DomainEncounterOM.withName('pollos')
    sut.addMonster(monsterToAdd)
    sut.removeMonster(monsterToRemove)
    const monsters = sut.monsters()
    expect(monsters).toHaveLength(0)
  })

  test('it should return proper visitor result', () => {
    const sut = DomainEncounterOM.withName('pollos')
    const result = sut.visit(visitor)
    expect(result).toBe(sut.id().value())
  })

  test('It should change status when encounter is done', () => {
    const sut = DomainEncounterOM.withName('pollos')
    sut.finish()
    expect(sut.status()).toBe(EncounterStatus.DONE)
  })

  test('It should generate event when encounter is done', () => {
    const monsterToAdd = EncounterMonster.fromValues('Pollo Papi', 2500, '1/2')
    const sut = DomainEncounterOM.withName('pollos')
    sut.addMonster(monsterToAdd)
    sut.addMonster(monsterToAdd)
    sut.finish()
    const events = sut.pullEvents()
    expect(events).toHaveLength(1)
    const event = events[0]
    expect(event.name()).toBe('EncounterWasFinished')
    expect(event.occurredOn()).toBeInstanceOf(Date)
    const expectedPayload = { encounterId: sut.id().value(), totalXp: 5000 }
    expect(event.payload()).toStrictEqual(expectedPayload)
    expect(sut.pullEvents()).toHaveLength(0)
  })

  test('It should update level properly', () => {
    const monsterToAdd = EncounterMonster.fromValues('Pollo Papi', 550, '1/2')
    const sut = DomainEncounterOM.withName('pollos')
    sut.addMonster(monsterToAdd)
    sut.updateLevel(new PartyTresholdDto([9]))
    expect(sut.level()).toBe(EncounterLevel.EASY)
  })
})
