import { Experience } from '@/Domain/Character/Experience'
import { Party } from '@/Domain/Character/Party/Party'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'

class NotUpdatablePartyDummy implements Party {
  // eslint-disable-next-line
  updateExperience (experiencePoints: Experience): void {
    throw new RangeError('This shall fail')
  }
}

export class NotUpdatablePartyRepositoryDummy implements PartyRepository {
  find (): Promise<Party> {
    return Promise.resolve(new NotUpdatablePartyDummy())
  }
}
