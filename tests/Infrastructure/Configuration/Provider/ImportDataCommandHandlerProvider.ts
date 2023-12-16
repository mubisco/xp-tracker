import { ImportDataCommandHandler } from '@/Application/Configuration/Command/ImportDataCommandHandler'
import { LocalStorageConfigurationRepository } from '@/Infrastructure/Persistence/LocalStorage/LocalStorageConfigurationRepository'

export class ImportDataCommandHandlerProvider {
  provide (): ImportDataCommandHandler {
    return new ImportDataCommandHandler(new LocalStorageConfigurationRepository())
  }
}
