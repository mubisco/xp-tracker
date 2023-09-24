import { AddEncounterCommand } from './AddEncounterCommand'
import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterName } from '@/Domain/Encounter/EncounterName'

export class AddEncounterCommandHandler {
  // eslint-disable-next-line
  constructor (private readonly writeModel: AddEncounterWriteModel) {}

  async handle (command: AddEncounterCommand): Promise<void> {
    const encounter = DomainEncounter.withName(EncounterName.fromString(command.name))
    return this.writeModel.write(encounter)
  }
}
