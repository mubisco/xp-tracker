import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DeleteMonsterFromEncounterCommand } from './DeleteMonsterFromEncounterCommand'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { MonsterNotFoundError } from '@/Domain/Encounter/MonsterNotFoundError'

export class DeleteMonsterFromEncounterCommandHandler {
  // eslint-disable-next-line
  constructor (private readonly repository: EncounterRepository) {}

  async handle (command: DeleteMonsterFromEncounterCommand): Promise<void> {
    const ulid = Ulid.fromString(command.encounterUlid)
    EncounterMonster.fromValues(command.monsterName, command.monsterXp, command.monsterCr)
    const encounter = this.repository.byUlid(ulid)
    throw new MonsterNotFoundError()
  }
}
