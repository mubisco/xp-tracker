import { ConfigurationReadModel } from '@/Domain/Configuration/ConfigurationReadModel'
import { LocalStorageTag } from '../LocalStorageTag'

export class LocalStorageConfigurationRepository implements ConfigurationReadModel {
  toBase64 (): Promise<string> {
    const characters = localStorage.getItem(LocalStorageTag.CHARACTERS) ?? '{}'
    const encounters = localStorage.getItem(LocalStorageTag.ENCOUNTERS) ?? '{}'
    const data = { characters: JSON.parse(characters), encounters: JSON.parse(encounters) }
    return Promise.resolve(btoa(JSON.stringify(data)))
  }
}
