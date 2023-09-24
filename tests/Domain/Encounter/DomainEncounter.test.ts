import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { describe, expect, test } from 'vitest'
import { DomainEncounterOM } from './DomainEncounterOM'
import { SimpleStringEncounterVisitor } from './SimpleStringEncounterVisitor'

describe('Testing DomainEncounter', () => {
  const visitor = new SimpleStringEncounterVisitor()

  test('It should return proper values', () => {
    const sut = DomainEncounterOM.withName('pollos')
    expect(sut.id()).toBeInstanceOf(Ulid)
    expect(sut.name()).toBeInstanceOf(EncounterName)
    expect(sut.name().value()).toBe('pollos')
  })
  test('it should return proper visitor result', () => {
    const sut = DomainEncounterOM.withName('pollos')
    const result = sut.visit(visitor)
    expect(result).toBe(sut.id().value())
  })
})
