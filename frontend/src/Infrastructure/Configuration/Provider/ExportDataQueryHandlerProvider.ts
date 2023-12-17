import { ExportDataQueryHandler } from '@/Application/Configuration/Query/ExportDataQueryHandler'
import { LocalStorageConfigurationRepository } from '@/Infrastructure/Persistence/LocalStorage/LocalStorageConfigurationRepository'

export class ExportDataQueryHandlerProvider {
  provide (): ExportDataQueryHandler {
    return new ExportDataQueryHandler(new LocalStorageConfigurationRepository())
  }
}
