<script lang="ts" setup>
import { FindEncounterByIdQuery } from '@/Application/Encounter/Query/FindEncounterByIdQuery'
import { DeleteMonsterFromEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/DeleteMonsterFromEncounterCommandHandlerProvider'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { FindEncounterByIdQueryHandlerProvider } from '@/Infrastructure/Encounter/Provider/FindEncounterByIdQueryHandlerProvider'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import EncounterDetails from './EncounterDetails.vue'
import AddEncounterDetailForm from './AddEncounterDetailForm.vue'
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import { DeleteMonsterFromEncounterCommand } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommand'

const provider = new FindEncounterByIdQueryHandlerProvider()
const deleteProvider = new DeleteMonsterFromEncounterCommandHandlerProvider()

const encounter = ref<EncounterDto | null>(null)
const route = useRoute()
const encounterName = computed((): string => {
  return encounter.value ? encounter.value.name : ''
})
const encounterUlid = computed((): string => {
  const routeId = route.params.encounterId ?? ''
  return routeId as string
})

const loadEncounter = async () => {
  const useCase = provider.provide()
  const query = new FindEncounterByIdQuery(encounterUlid.value)
  encounter.value = await useCase.handle(query)
}

const onDeleteMonsterClicked = async (payload: MonsterDto) => {
  const handler = deleteProvider.provide()
  const command = new DeleteMonsterFromEncounterCommand(
    encounterUlid.value,
    payload.name,
    payload.xp,
    payload.cr
  )
  await handler.handle(command)
  await loadEncounter()
}

onMounted(loadEncounter)

</script>

<template>
  <v-card>
    <template #title>
      Edit {{ encounterName }} Encounter
    </template>
    <template #text>
      <EncounterDetails
        :monsters="encounter !== null ? encounter.monsters : []"
        :show-delete-button="true"
        @monster:deleted="onDeleteMonsterClicked"
      />
      <AddEncounterDetailForm
        :encounter-ulid="encounterUlid"
        @monster:added="loadEncounter"
      />
    </template>
    <v-card-actions class="justify-start">
      <v-btn
        color="primary"
        prepend-icon="mdi-arrow-left"
        :active="false"
        :to="{ name: 'Encounter' }"
      >
        Back
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
