<script lang="ts" setup>
import { FinishEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/FinishEncounterCommandHandlerProvider'
import { inject } from 'vue'
import { FinishEncounterCommand } from '@/Application/Encounter/Command/FinishEncounterCommand'
import { EventBus } from '@/Domain/Shared/Event/EventBus'

const props = defineProps<{ encounterUlid: string }>()

const provider = new FinishEncounterCommandHandlerProvider()
const eventBus = inject('eventBus')

const onAddXpButtonClicked = async (): Promise<void> => {
  if (!eventBus) {
    throw Error('No event bus configured')
  }
  const handler = provider.provide(eventBus as EventBus)
  const command = new FinishEncounterCommand(props.encounterUlid)
  await handler.handle(command)
}

</script>
<template>
  <v-btn
    variant="elevated"
    color="primary"
    prepend-icon="mdi-content-save"
    @click="onAddXpButtonClicked()"
  >
    Add XP
  </v-btn>
</template>
