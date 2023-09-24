import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'

const LOCALSTORAGE_TAG = 'encounters'

export class LocalStorageEncounterRepository implements AddEncounterWriteModel {
  // eslint-disable-next-line
  constructor (private readonly visitor: EncounterVisitor<any>) {}

  async write (encounter: Encounter): Promise<void> {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    const data = JSON.parse(result)
    const characterKeys = Object.keys(data)
    if (characterKeys.indexOf(encounter.id().value()) > -1) {
      throw new AddEncounterWritelModelError(`Encounter id ${encounter.id().value()} already exists!!!`)
    }
    data[encounter.id().value()] = encounter.visit(this.visitor)
    const stringifiedResult = JSON.stringify(data)
    localStorage.setItem(LOCALSTORAGE_TAG, stringifiedResult)
  }
}
