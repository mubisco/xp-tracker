import { AllPartiesQuery } from '@/Application/Party/Query/AllPartiesQuery'
import { AllPartiesQueryHandler } from '@/Application/Party/Query/AllPartiesQueryHandler'
import { PartyReadModelStub } from '@tests/Domain/Party/PartyReadModelStub'

import { describe, expect, test } from 'vitest'
describe('Testing AllPartiesQueryHandler', () => {
  test('It should return proper data', async () => {
    const sut = new AllPartiesQueryHandler(new PartyReadModelStub())
    const query = new AllPartiesQuery()
    const result = await sut.handle(query)
    expect(result).toHaveLength(3)
  })
})
