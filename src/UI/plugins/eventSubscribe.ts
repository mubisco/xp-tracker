import { App } from 'vue'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { VueEventBus } from '@/Infrastructure/Shared/Event/VueEventBus'
import { UpdatePartyExperienceWhenEncounterWasFinishedProvider } from '@/Infrastructure/Character/Provider/UpdatePartyExperienceWhenEncounterWasFinishedProvider'

export function eventSubscribe (app: App): void {
  const vueEventBus = new VueEventBus()
  const provider = new UpdatePartyExperienceWhenEncounterWasFinishedProvider()

  vueEventBus.subscribe(provider.provide())
  app.provide<EventBus>('eventBus', vueEventBus)
}
