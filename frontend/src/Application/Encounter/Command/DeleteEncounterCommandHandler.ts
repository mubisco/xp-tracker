import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DeleteEncounterCommand } from './DeleteEncounterCommand'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { DeleteEncounterWriteModel } from '@/Domain/Encounter/DeleteEncounterWriteModel'

export class DeleteEncounterCommandHandler {
  // eslint-disable-next-line
  constructor (
    private readonly repository: EncounterRepository,
    private readonly writeModel: DeleteEncounterWriteModel
  ) {}

  async handle (command: DeleteEncounterCommand): Promise<void> {
    const ulid = Ulid.fromString(command.encounterUlid)
    const encounter = await this.repository.byUlid(ulid)
    await this.writeModel.remove(encounter)
  }
}
