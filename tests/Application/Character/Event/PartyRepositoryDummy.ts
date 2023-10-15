import { Experience } from '@/Domain/Character/Experience'
import { Party } from '@/Domain/Character/Party/Party'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'

class PartyDummySpy implements Party {
  public experiencePoints: number = 0
  // eslint-disable-next-line
  updateExperience (experiencePoints: Experience): void {
    this.experiencePoints = experiencePoints.values().actual
  }
}

export class PartyRepositoryDummy implements PartyRepository {
  find (): Promise<Party> {
    return Promise.resolve(new PartyDummySpy())
  }
}
