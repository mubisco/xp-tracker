import { PartyReadModel } from '@/Domain/Party/PartyReadModel'
import { AllPartiesQuery } from './AllPartiesQuery'
import { PartyDto } from '@/Domain/Party/PartyDto'

export class AllPartiesQueryHandler {
  // eslint-disable-next-line
  constructor(private readonly readModel: PartyReadModel) {}

  // eslint-disable-next-line
  async handle (query: AllPartiesQuery): Promise<PartyDto[]> {
    return await this.readModel.allParties()
  }
}
