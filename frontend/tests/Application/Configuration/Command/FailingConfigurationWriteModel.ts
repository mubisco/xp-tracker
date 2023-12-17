import { ConfigurationContent } from '@/Domain/Configuration/ConfigurationContent'
import { ConfigurationWriteModel } from '@/Domain/Configuration/ConfigurationWriteModel'
import { ConfigurationWriteModelError } from '@/Domain/Configuration/ConfigurationWriteModelError'

export class FailingConfigurationWriteModel implements ConfigurationWriteModel {
  // eslint-disable-next-line
  async writeConfiguration (content: ConfigurationContent): Promise<void> {
    throw new ConfigurationWriteModelError('Method not implemented.')
  }
}
