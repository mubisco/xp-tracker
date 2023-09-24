import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { Encounter } from '@/Domain/Encounter/Encounter'

const LOCALSTORAGE_TAG = 'encounters'

export class LocalStorageEncounterRepository implements AddEncounterWriteModel {
  async write (encounter: Encounter): Promise<void> {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    const data = JSON.parse(result)
    const characterKeys = Object.keys(data)
    if (characterKeys.indexOf(encounter.id().value()) > -1) {
      throw new AddEncounterWritelModelError(`Encounter id ${encounter.id().value()} already exists!!!`)
    }
    data[encounter.id().value()] = {}
    const stringifiedResult = JSON.stringify(data)
    localStorage.setItem(LOCALSTORAGE_TAG, stringifiedResult)
  }
}
