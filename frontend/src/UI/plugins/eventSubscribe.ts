import { App } from 'vue'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { VueEventBus } from '@/Infrastructure/Shared/Event/VueEventBus'

export function eventSubscribe (app: App): void {
  const vueEventBus = new VueEventBus()
  // vueEventBus.subscribe(provider.provide(vueEventBus))
  app.provide<EventBus>('eventBus', vueEventBus)
}
