import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'
import { FindEncounterReadModel } from '@/Domain/Encounter/FindEncounterReadModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { LocalStorageEncounterFactory } from './LocalStorageEncounterFactory'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'
import { AllEncountersReadModel } from '@/Domain/Encounter/AllEncountersReadModel'
import { DeleteEncounterWriteModel } from '@/Domain/Encounter/DeleteEncounterWriteModel'

const LOCALSTORAGE_TAG = 'encounters'

interface RawEncounterData { [key: string]: string }

export class LocalStorageEncounterRepository
implements AddEncounterWriteModel, DeleteEncounterWriteModel, FindEncounterReadModel, EncounterRepository, UpdateEncounterWriteModel, AllEncountersReadModel {
  private rawEncounterData: RawEncounterData = {}

  // eslint-disable-next-line
  constructor (
    private readonly visitor: EncounterVisitor<string>,
    private readonly factory: LocalStorageEncounterFactory
  ) {
    this.readEncounterData()
  }

  allEncounters (): Promise<Encounter[]> {
    const encounters: Encounter[] = []
    for (const encounterUlid in this.rawEncounterData) {
      encounters.push(this.factory.make(this.rawEncounterData[encounterUlid]))
    }
    return Promise.resolve(encounters)
  }

  remove (encounter: Encounter): Promise<void> {
    const updatedEncounterData: RawEncounterData = { ...this.rawEncounterData }
    delete (updatedEncounterData[encounter.id().value()])
    this.rawEncounterData = updatedEncounterData
    this.updateStorage()
    return Promise.resolve()
  }

  all (): Promise<EncounterDto[]> {
    const parsedEncounters: EncounterDto[] = []
    for (const encounterId in this.rawEncounterData) {
      const currentEncounter = this.rawEncounterData[encounterId]
      parsedEncounters.push(JSON.parse(currentEncounter))
    }
    return Promise.resolve(parsedEncounters)
  }

  async update (encounter: Encounter): Promise<void> {
    if (!this.encounterKeyExists(encounter.id())) {
      throw new EncounterNotFoundError(`Encounter ${encounter.id().value()} not found !!!`)
    }
    this.rawEncounterData[encounter.id().value()] = encounter.visit(this.visitor)
    this.updateStorage()
  }

  async byUlid (ulid: Ulid): Promise<Encounter> {
    if (!this.encounterKeyExists(ulid)) {
      throw new EncounterNotFoundError(`Encounter ${ulid.value()} not found !!!`)
    }
    const rawEncounter = this.rawEncounterData[ulid.value()]
    return this.factory.make(rawEncounter)
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
