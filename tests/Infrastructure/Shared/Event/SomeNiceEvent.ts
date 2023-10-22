import { DomainEvent } from '@/Domain/Shared/Event/DomainEvent'

export class SomeNiceEvent implements DomainEvent {
  occurredOn (): Date {
    throw new Error('Method not implemented.')
  }

  name (): string {
    return 'SomeNiceEvent'
  }

  payload (): { [key: string]: any } {
    return { asd: 'asd' }
  }
}
