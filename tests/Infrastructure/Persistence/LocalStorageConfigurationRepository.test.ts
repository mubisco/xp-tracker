import { LocalStorageConfigurationRepository } from '@/Infrastructure/Persistence/LocalStorage/LocalStorageConfigurationRepository'
import { describe, expect, test, beforeEach } from 'vitest'
import exampleData from './exampleData.json'

describe('Testing LocalStorageConfigurationRepository', () => {
  beforeEach(() => {
    localStorage.removeItem('characters')
    localStorage.removeItem('encounters')
  })
  test('It should return proper data', async () => {
    const characters = JSON.stringify(exampleData.characters)
    const encounters = JSON.stringify(exampleData.encounters)
    localStorage.setItem('characters', characters)
    localStorage.setItem('encounters', encounters)
    const sut = new LocalStorageConfigurationRepository()
    const result = await sut.toBase64()
    expect(JSON.parse(atob(result))).toStrictEqual(exampleData)
  })
})
