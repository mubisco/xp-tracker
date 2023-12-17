import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DeleteMonsterFromEncounterCommand } from './DeleteMonsterFromEncounterCommand'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'

export class DeleteMonsterFromEncounterCommandHandler {
  // eslint-disable-next-line
  constructor (
    private readonly repository: EncounterRepository,
    private readonly writeModel: UpdateEncounterWriteModel
  ) {}

  async handle (command: DeleteMonsterFromEncounterCommand): Promise<void> {
    const ulid = Ulid.fromString(command.encounterUlid)
    const monster = EncounterMonster.fromValues(command.monsterName, command.monsterXp, command.monsterCr)
    const encounter = await this.repository.byUlid(ulid)
    encounter.removeMonster(monster)
    await this.writeModel.update(encounter)
    return Promise.resolve()
  }
}
