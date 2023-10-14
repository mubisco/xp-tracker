import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { AddEncounterExperienceToPartyCommand } from './AddEncounterExperienceToPartyCommand'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { EmptyPartyError } from './EmptyPartyError'

export class AddEncounterExperienceToPartyCommandHandler {
  // eslint-disable-next-line
  constructor (private readonly encounterRepository: EncounterRepository) {}

  async handle (command: AddEncounterExperienceToPartyCommand): Promise<void> {
    const encounterUlid = Ulid.fromString(command.encounterUlid)
    this.encounterRepository.byUlid(encounterUlid)
    throw new EmptyPartyError()
  }
}
