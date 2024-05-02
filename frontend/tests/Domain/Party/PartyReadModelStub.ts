import { PartyDto } from '@/Domain/Party/PartyDto'
import { PartyReadModel } from '@/Domain/Party/PartyReadModel'

export class PartyReadModelStub implements PartyReadModel {
  allParties (): Promise<PartyDto[]> {
    const data = [
      { partyUlid: '01HWX4C1SRR89VSZG1SMDTAY02', partyName: 'Cuchufletas', partyCharacters: 0 },
      { partyUlid: '01HWX4C3R8ZGPXKVRG8NMM4H0G', partyName: 'Chindas', partyCharacters: 3 },
      { partyUlid: '01HWX4C7N8CZBYXRTEPE65MRCT', partyName: 'Chachis', partyCharacters: 5 }
    ]
    return Promise.resolve(data)
  }
}

