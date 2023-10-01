import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'
import { FindEncounterReadModel } from '@/Domain/Encounter/FindEncounterReadModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

const LOCALSTORAGE_TAG = 'encounters'

interface RawEncounterData { [key: string]: string }

export class LocalStorageEncounterRepository implements AddEncounterWriteModel, FindEncounterReadModel {
  private rawEncounterData: RawEncounterData = {}

  // eslint-disable-next-line
  constructor (private readonly visitor: EncounterVisitor<string>) {
    this.readEncounterData()
  }

  async byId (ulid: Ulid): Promise<EncounterDto> {
    if (!this.encounterKeyExists(ulid)) {
      throw new EncounterNotFoundError(`Encounter ${ulid.value()} not found !!!`)
    }
    const rawEncounter = this.rawEncounterData[ulid.value()]
    return JSON.parse(rawEncounter)
  }

  async write (encounter: Encounter): Promise<void> {
    if (this.encounterKeyExists(encounter.id())) {
      throw new AddEncounterWritelModelError(`Encounter id ${encounter.id().value()} already exists!!!`)
    }
    this.rawEncounterData[encounter.id().value()] = encounter.visit(this.visitor)
    this.updateStorage()
  }

  private readEncounterData (): void {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    this.rawEncounterData = JSON.parse(result)
  }

  private encounterKeyExists (ulid: Ulid): boolean {
    const encounterKeys = Object.keys(this.rawEncounterData)
    return encounterKeys.indexOf(ulid.value()) > -1
  }

  private updateStorage (): void {
    const stringifiedResult = JSON.stringify(this.rawEncounterData)
    localStorage.setItem(LOCALSTORAGE_TAG, stringifiedResult)
  }
}
