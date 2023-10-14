import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { FinishEncounterCommand } from './FinishEncounterCommand'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'
import { EventBus } from '@/Domain/Shared/Event/EventBus'

export class FinishEncounterCommandHandler {
  // eslint-disable-next-line
  constructor (
    private readonly encounterRepository: EncounterRepository,
    private readonly writeModel: UpdateEncounterWriteModel,
    private readonly eventBus: EventBus
  ) {}

  async handle (command:  FinishEncounterCommand): Promise<void> {
    const encounterUlid = Ulid.fromString(command.encounterUlid)
    const encounter = await this.encounterRepository.byUlid(encounterUlid)
    encounter.finish()
    await this.writeModel.update(encounter)
    this.eventBus.publish(encounter.pullEvents())
    return Promise.resolve()
  }
}
