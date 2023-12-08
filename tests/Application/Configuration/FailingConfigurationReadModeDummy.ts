import { ConfigurationReadModel } from '@/Domain/Configuration/ConfigurationReadModel'
import { ConfigurationReadModelError } from '@/Domain/Configuration/ConfigurationReadModelError'

export class FailingConfigurationReadModelDummy implements ConfigurationReadModel {
  toBase64 (): Promise<string> {
    throw new ConfigurationReadModelError('Method not implemented.')
  }
}
