import { AllEncountersReadModel } from '@/Domain/Encounter/AllEncountersReadModel'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'

export class EmptyAllEncountersReadModelDummy implements AllEncountersReadModel {
  all (): Promise<EncounterDto[]> {
    return Promise.resolve([])
  }
}
