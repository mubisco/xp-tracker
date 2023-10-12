import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'
import { FindEncounterReadModel } from '@/Domain/Encounter/FindEncounterReadModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { LocalStorageEncounterFactory } from './LocalStorageEncounterFactory'

const LOCALSTORAGE_TAG = 'encounters'

interface RawEncounterData { [key: string]: string }

export class LocalStorageEncounterRepository implements AddEncounterWriteModel, FindEncounterReadModel, EncounterRepository {
  private rawEncounterData: RawEncounterData = {}

  // eslint-disable-next-line
  constructor (
    private readonly visitor: EncounterVisitor<string>,
    private readonly factory: LocalStorageEncounterFactory
  ) {
    this.readEncounterData()
  }

  async byUlid (ulid: Ulid): Promise<Encounter> {
    const encounterData = await this.byId(ulid)
    return this.factory.make(encounterData)
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
