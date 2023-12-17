import { ConfigurationContent } from '@/Domain/Configuration/ConfigurationContent'
import { ConfigurationWriteModel } from '@/Domain/Configuration/ConfigurationWriteModel'

export class DummyConfigurationWriteModel implements ConfigurationWriteModel {
  public updated = false
  // eslint-disable-next-line
  async writeConfiguration (content: ConfigurationContent): Promise<void> {
    this.updated = true
    return Promise.resolve()
  }
}
