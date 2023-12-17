import { ConfigurationContent } from './ConfigurationContent'

export interface ConfigurationWriteModel {
  writeConfiguration(content: ConfigurationContent): Promise<void>
}
