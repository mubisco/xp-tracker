import { AllEncountersReadModel } from '@/Domain/Encounter/AllEncountersReadModel'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'

export class AllEncountersQueryHandler {
  // eslint-disable-next-line
  constructor (private readonly readModel: AllEncountersReadModel) {}

  async handle (): Promise<EncounterDto[]> {
    return this.readModel.all()
  }
}
