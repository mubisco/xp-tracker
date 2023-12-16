import { ConfigurationReadModel } from '@/Domain/Configuration/ConfigurationReadModel'
import { LocalStorageTag } from '../LocalStorageTag'
import { ConfigurationWriteModel } from '@/Domain/Configuration/ConfigurationWriteModel'
import { ConfigurationContent } from '@/Domain/Configuration/ConfigurationContent'

export class LocalStorageConfigurationRepository implements ConfigurationReadModel, ConfigurationWriteModel {
  async writeConfiguration (content: ConfigurationContent): Promise<void> {
    const parsedData = JSON.parse(content.value())
    const characters = parsedData.characters
    const encounters = parsedData.encounters
    localStorage.setItem(LocalStorageTag.CHARACTERS, JSON.stringify(characters))
    localStorage.setItem(LocalStorageTag.ENCOUNTERS, JSON.stringify(encounters))
  }

  async toBase64 (): Promise<string> {
    const characters = localStorage.getItem(LocalStorageTag.CHARACTERS) ?? '{}'
    const encounters = localStorage.getItem(LocalStorageTag.ENCOUNTERS) ?? '{}'
    const data = { characters: JSON.parse(characters), encounters: JSON.parse(encounters) }
    return Promise.resolve(btoa(JSON.stringify(data)))
  }
}
