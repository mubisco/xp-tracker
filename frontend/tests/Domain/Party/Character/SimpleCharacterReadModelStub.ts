import { SimpleCharacter } from '@/Domain/Party/Character/SimpleCharacter'
import { SimpleCharacterReadModel } from '@/Domain/Party/Character/SimpleCharacterReadModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class SimpleCharacterReadModelStub implements SimpleCharacterReadModel {
  // eslint-disable-next-line
  async ofPartyId (partyUlid: Ulid): Promise<SimpleCharacter[]> {
    const data = [
      { ulid: '01HWZ21VX8W6KPKZ68S1NPKD9X', name: 'Chindas', xp: 200, next: 300, level: 1 }
    ]
    return Promise.resolve(data)
  }
}
