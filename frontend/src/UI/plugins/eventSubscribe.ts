import { App } from 'vue'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { VueEventBus } from '@/Infrastructure/Shared/Event/VueEventBus'
import { UpdatePartyExperienceWhenEncounterWasFinishedProvider } from '@/Infrastructure/Character/Provider/UpdatePartyExperienceWhenEncounterWasFinishedProvider'
import { UpdateEncounterLevelsWhenPartyUpdatedProvider } from '@/Infrastructure/Encounter/Provider/UpdateEncounterLevelsWhenPartyUpdatedProvider'

export function eventSubscribe (app: App): void {
  const vueEventBus = new VueEventBus()
  const provider = new UpdatePartyExperienceWhenEncounterWasFinishedProvider()
  const updateEncounterLevelWhenPartyWasUpdatedProvider = new UpdateEncounterLevelsWhenPartyUpdatedProvider()

  vueEventBus.subscribe(provider.provide(vueEventBus))
  vueEventBus.subscribe(updateEncounterLevelWhenPartyWasUpdatedProvider.provide())
  app.provide<EventBus>('eventBus', vueEventBus)
}
