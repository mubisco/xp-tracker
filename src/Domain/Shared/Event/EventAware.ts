import { DomainEvent } from './DomainEvent'

export interface EventAware {
  pullEvents (): DomainEvent[];
}
