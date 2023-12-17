import { ConfigurationContent } from '@/Domain/Configuration/ConfigurationContent'
import { describe, expect, test } from 'vitest'

const encodedContent = 'eyJjaGFyYWN0ZXJzIjp7IjAxSEdaRkhXQU5QWjRaREEzQ0YxNFZCUTFXIjoiYXNkIiwiMDFIR1pGSjYzTjBNR0FYMkM4V0ZKTUpUM0EiOiJxd2UiLCIwMUhHWkZKUjZNWkc5REE4WDhQWVo0MUgxOCI6Inp4YyIsIjAxSEdaRksyVDVNSFMyN1c5QTNRUEpYWTExIjoicnR5IiwiMDFIR1pGS0VQOFMzSFhQQ05GU0RNU0FXQlAiOiJjeHpkcyJ9LCJlbmNvdW50ZXJzIjp7IjAxSEdaRk1NRlkwV0Y5OEo5Rk1QWkRaS0pWIjoiZGZnIiwiMDFIR1pGUUtYNDBXUFA1NTdDQ1FKSFFEUkUiOiJydHkiLCIwMUhHWkZSWjM5MDBaU1hFTUIxSEFGNjUwRSI6ImN2YiIsIjAxSEdaR0FZUEsxRlpUUlFFV0tSWEJFM1g4IjoiaGprIn19'

const encodedContentWithoutEncountersSection = 'eyJjaGFyYWN0ZXJzIjp7IjAxSEdaRkhXQU5QWjRaREEzQ0YxNFZCUTFXIjoiYXNkIiwiMDFIR1pGSjYzTjBNR0FYMkM4V0ZKTUpUM0EiOiJxd2UiLCIwMUhHWkZKUjZNWkc5REE4WDhQWVo0MUgxOCI6Inp4YyIsIjAxSEdaRksyVDVNSFMyN1c5QTNRUEpYWTExIjoicnR5IiwiMDFIR1pGS0VQOFMzSFhQQ05GU0RNU0FXQlAiOiJjeHpkcyJ9'

const encodedContentWithoutCharactersSection = 'eyJlbmNvdW50ZXJzIjp7IjAxSEdaRk1NRlkwV0Y5OEo5Rk1QWkRaS0pWIjoiZGZnIiwiMDFIR1pGUUtYNDBXUFA1NTdDQ1FKSFFEUkUiOiJydHkiLCIwMUhHWkZSWjM5MDBaU1hFTUIxSEFGNjUwRSI6ImN2YiIsIjAxSEdaR0FZUEsxRlpUUlFFV0tSWEJFM1g4IjoiaGprIn19'

const decodedContent = '{"characters":{"01HGZFHWANPZ4ZDA3CF14VBQ1W":"asd","01HGZFJ63N0MGAX2C8WFJMJT3A":"qwe","01HGZFJR6MZG9DA8X8PYZ41H18":"zxc","01HGZFK2T5MHS27W9A3QPJXY11":"rty","01HGZFKEP8S3HXPCNFSDMSAWBP":"cxzds"},"encounters":{"01HGZFMMFY0WF98J9FMPZDZKJV":"dfg","01HGZFQKX40WPP557CCQJHQDRE":"rty","01HGZFRZ3900ZSXEMB1HAF650E":"cvb","01HGZGAYPK1FZTRQEWKRXBE3X8":"hjk"}}'

describe('Testing ConfigurationContent', () => {
  test('It should throw error if empty', () => {
    expect(() => {
      ConfigurationContent.fromBase64Content('')
    }).toThrow(RangeError)
  })
  test('It should throw exception when content does not have encounters data', () => {
    expect(() => {
      ConfigurationContent.fromBase64Content(encodedContentWithoutEncountersSection)
    }).toThrow(RangeError)
  })
  test('It should throw exception when content does not have characters data', () => {
    expect(() => {
      ConfigurationContent.fromBase64Content(encodedContentWithoutCharactersSection)
    }).toThrow(RangeError)
  })
  test('It should return value decoded', () => {
    const sut = ConfigurationContent.fromBase64Content(encodedContent)
    expect(sut.value()).toBe(decodedContent)
  })
})
