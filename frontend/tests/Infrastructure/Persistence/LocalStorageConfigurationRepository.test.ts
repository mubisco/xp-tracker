import { LocalStorageConfigurationRepository } from '@/Infrastructure/Persistence/LocalStorage/LocalStorageConfigurationRepository'
import { describe, expect, test, beforeEach } from 'vitest'
import exampleData from './exampleData.json'
import { ConfigurationContent } from '@/Domain/Configuration/ConfigurationContent'

const encodedContent = 'eyJjaGFyYWN0ZXJzIjp7IjAxSEdaRkhXQU5QWjRaREEzQ0YxNFZCUTFXIjoiYXNkIiwiMDFIR1pGSjYzTjBNR0FYMkM4V0ZKTUpUM0EiOiJxd2UiLCIwMUhHWkZKUjZNWkc5REE4WDhQWVo0MUgxOCI6Inp4YyIsIjAxSEdaRksyVDVNSFMyN1c5QTNRUEpYWTExIjoicnR5IiwiMDFIR1pGS0VQOFMzSFhQQ05GU0RNU0FXQlAiOiJjeHpkcyJ9LCJlbmNvdW50ZXJzIjp7IjAxSEdaRk1NRlkwV0Y5OEo5Rk1QWkRaS0pWIjoiZGZnIiwiMDFIR1pGUUtYNDBXUFA1NTdDQ1FKSFFEUkUiOiJydHkiLCIwMUhHWkZSWjM5MDBaU1hFTUIxSEFGNjUwRSI6ImN2YiIsIjAxSEdaR0FZUEsxRlpUUlFFV0tSWEJFM1g4IjoiaGprIn19'

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

  test('It should store data properly', async () => {
    const content = ConfigurationContent.fromBase64Content(encodedContent)
    const sut = new LocalStorageConfigurationRepository()
    await sut.writeConfiguration(content)
    const characters = localStorage.getItem('characters')
    const encounters = localStorage.getItem('encounters')
    const expectedCharacters = JSON.stringify(exampleData.characters)
    const expectedEncounters = JSON.stringify(exampleData.encounters)
    expect(characters).toBe(expectedCharacters)
    expect(encounters).toBe(expectedEncounters)
  })
})
