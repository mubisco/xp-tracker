import { ExportFileName } from '@/Domain/Configuration/ExportFileName'
import { ExportDataQuery } from './ExportDataQuery'
import { ConfigurationReadModel } from '@/Domain/Configuration/ConfigurationReadModel'

export class ExportDataQueryHandler {
  // eslint-disable-next-line
  constructor (private readonly readModel: ConfigurationReadModel) {
  }

  async handle (query: ExportDataQuery): Promise<void> {
    const filename = ExportFileName.fromString(query.filename)
    const content = await this.readModel.toBase64()
    return { [filename.value()]: content }
  }
}
