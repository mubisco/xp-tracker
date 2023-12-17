import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { UpdateEncounterLevelCommand } from './UpdateEncounterLevelCommand'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'

export class UpdateEncounterLevelCommandHandler {
  // eslint-disable-next-line
  constructor (
    private readonly partyTresholdsReadModel: PartyTresholdsReadModel,
    private readonly encounterRepository: EncounterRepository,
    private readonly updateEncounterWriteModel: UpdateEncounterWriteModel
  ) {}

  async handle (command: UpdateEncounterLevelCommand): Promise<void> {
    const ulid = Ulid.fromString(command.encounterUlid)
    const partyTresholds = await this.partyTresholdsReadModel.fetchTresholds()
    const encounter = await this.encounterRepository.byUlid(ulid)
    encounter.updateLevel(partyTresholds)
    await this.updateEncounterWriteModel.update(encounter)
  }
}
