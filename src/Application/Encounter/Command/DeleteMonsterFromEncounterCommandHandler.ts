import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DeleteMonsterFromEncounterCommand } from './DeleteMonsterFromEncounterCommand'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'

export class DeleteMonsterFromEncounterCommandHandler {
  async handle (command: DeleteMonsterFromEncounterCommand): Promise<void> {
    Ulid.fromString(command.encounterUlid)
    throw new EncounterNotFoundError()
  }
}
