import { DomainEvent } from './DomainEvent'

export interface EventListener {
  listensTo (eventName: string): boolean
  handle (event: DomainEvent): Promise<any>
}
