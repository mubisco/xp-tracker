import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterReadModelError } from '@/Domain/Encounter/EncounterReadModelError'
import { FindEncounterReadModel } from '@/Domain/Encounter/FindEncounterReadModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class FindEncounterReadModelDummy implements FindEncounterReadModel {
  public shouldFail = false
  public shouldNotFind = false

  async byId (ulid: Ulid): Promise<EncounterDto> {
    if (this.shouldNotFind) {
      return Promise.reject(new EncounterNotFoundError())
    }
    if (this.shouldFail) {
      return Promise.reject(new EncounterReadModelError())
    }
    const response = {
      ulid: ulid.value()
    }
    return Promise.resolve(response)
  }
}
