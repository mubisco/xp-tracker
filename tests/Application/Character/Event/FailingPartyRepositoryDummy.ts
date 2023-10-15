import { Party } from '@/Domain/Character/Party/Party'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'
import { PartyRepositoryError } from '@/Domain/Character/Party/PartyRepositoryError'

export class FailingPartyRepositoryDummy implements PartyRepository {
  find (): Promise<Party> {
    throw new PartyRepositoryError('Opsie')
  }
}
