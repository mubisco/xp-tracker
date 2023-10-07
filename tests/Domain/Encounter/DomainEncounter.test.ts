import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { describe, expect, test } from 'vitest'
import { DomainEncounterOM } from './DomainEncounterOM'
import { SimpleStringEncounterVisitor } from './SimpleStringEncounterVisitor'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'

describe('Testing DomainEncounter', () => {
  const visitor = new SimpleStringEncounterVisitor()

  test('It should return proper values', () => {
    const sut = DomainEncounterOM.withName('pollos')
    expect(sut.id()).toBeInstanceOf(Ulid)
    expect(sut.name()).toBeInstanceOf(EncounterName)
    expect(sut.name().value()).toBe('pollos')
    expect(sut.monsters()).toHaveLength(0)
  })

  test('It should add a monster properly', () => {
    const monsterToAdd = EncounterMonster.fromValues('Pollo Papi', 2500, '1/2')
    const sut = DomainEncounterOM.withName('pollos')
    sut.addMonster(monsterToAdd)
    const monsters = sut.monsters()
    expect(monsters).toHaveLength(1)
    expect(monsters[0]).toStrictEqual(monsterToAdd)
  })

  test('it should return proper visitor result', () => {
    const sut = DomainEncounterOM.withName('pollos')
    const result = sut.visit(visitor)
    expect(result).toBe(sut.id().value())
  })
})
