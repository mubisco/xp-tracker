import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { describe, expect, test } from 'vitest'
import { DomainEncounterOM } from './DomainEncounterOM'

describe('Testing DomainEncounter', () => {
  test('It should return proper values', () => {
    const sut = DomainEncounterOM.withName('pollos')
    expect(sut.id()).toBeInstanceOf(Ulid)
    expect(sut.name()).toBeInstanceOf(EncounterName)
    expect(sut.name().value()).toBe('pollos')
  })
})
