import { ConfigurationContent } from '@/Domain/Configuration/ConfigurationContent'
import { ImportDataCommand } from './ImportDataCommand'
import { ConfigurationWriteModel } from '@/Domain/Configuration/ConfigurationWriteModel'

export class ImportDataCommandHandler {
  // eslint-disable-next-line
  constructor (private readonly writeModel: ConfigurationWriteModel) {}

  async handle (command: ImportDataCommand): Promise<void> {
    const config = ConfigurationContent.fromBase64Content(command.content)
    await this.writeModel.writeConfiguration(config)
    return Promise.resolve()
  }
}
