import { ExportFileName } from '@/Domain/Configuration/ExportFileName'
import { ExportDataQuery } from './ExportDataQuery'
import { ConfigurationReadModel } from '@/Domain/Configuration/ConfigurationReadModel'
import { ExportDataDto } from './ExportDataDto'

export class ExportDataQueryHandler {
  // eslint-disable-next-line
  constructor (private readonly readModel: ConfigurationReadModel) {
  }

  async handle (query: ExportDataQuery): Promise<ExportDataDto> {
    const filename = ExportFileName.fromString(query.filename)
    const content = await this.readModel.toBase64()
    return { filename: filename.value(), base64content: content }
  }
}
