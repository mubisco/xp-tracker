import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { FindEncounterByIdQuery } from './FindEncounterByIdQuery'
import { FindEncounterReadModel } from '@/Domain/Encounter/FindEncounterReadModel'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'

export class FindEncounterByIdQueryHandler {
  // eslint-disable-next-line
  constructor (private readonly readModel: FindEncounterReadModel) {}

  async handle (query: FindEncounterByIdQuery): Promise<EncounterDto> {
    const ulid = Ulid.fromString(query.encounterId)
    return await this.readModel.byId(ulid)
  }
}
