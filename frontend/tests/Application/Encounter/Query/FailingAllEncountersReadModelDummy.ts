import { AllEncountersReadModel } from '@/Domain/Encounter/AllEncountersReadModel'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterReadModelError } from '@/Domain/Encounter/EncounterReadModelError'

export class FailingAllEncountersReadModelDummy implements AllEncountersReadModel {
  all (): Promise<EncounterDto[]> {
    throw new EncounterReadModelError()
  }
}
