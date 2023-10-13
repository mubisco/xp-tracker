import { AllEncountersReadModel } from '@/Domain/Encounter/AllEncountersReadModel'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'

export class AllEncountersReadModelDummy implements AllEncountersReadModel {
  all (): Promise<EncounterDto[]> {
    return Promise.resolve([{
      ulid: '01HBNET1J3ZDVF8HWJM2TQCC13',
      name: 'Encounter random',
      monsters: []
    }])
  }
}
