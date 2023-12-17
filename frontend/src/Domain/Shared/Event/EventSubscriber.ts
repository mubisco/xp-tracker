import { EventListener } from '@/Domain/Shared/Event/EventListener'

export interface EventSubscriber {
  subscribe (listener: EventListener): void
}
