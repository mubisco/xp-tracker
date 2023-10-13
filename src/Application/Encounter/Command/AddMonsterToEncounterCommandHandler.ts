import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { AddMonsterToEncounterCommand } from './AddMonsterToEncounterCommand'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'

export class AddMonsterToEncounterCommandHandler {
  // eslint-disable-next-line
  constructor(
    private readonly encounterRepository: EncounterRepository,
    private readonly updateEncounterWriteModel: UpdateEncounterWriteModel
  ) {}

  async handle (command: AddMonsterToEncounterCommand): Promise<void> {
    const ulid = Ulid.fromString(command.encounterUlid)
    const monster = EncounterMonster.fromValues(command.monsterName, command.experiencePoints, command.challengeRating)
    const encounter = await this.encounterRepository.byUlid(ulid)
    encounter.addMonster(monster)
    await this.updateEncounterWriteModel.update(encounter)
  }
}
