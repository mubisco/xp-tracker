import { ConfigurationReadModel } from '@/Domain/Configuration/ConfigurationReadModel'

export class ConfigurationReadModelStub implements ConfigurationReadModel {
  toBase64 (): Promise<string> {
    return Promise.resolve('asdasdasd')
  }
}
