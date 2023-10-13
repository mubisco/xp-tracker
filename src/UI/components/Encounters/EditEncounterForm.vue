<script lang="ts" setup>
import { FindEncounterByIdQuery } from '@/Application/Encounter/Query/FindEncounterByIdQuery'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { FindEncounterByIdQueryHandlerProvider } from '@/Infrastructure/Encounter/Provider/FindEncounterByIdQueryHandlerProvider'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import EncounterDetails from './EncounterDetails.vue'
import AddEncounterDetailForm from './AddEncounterDetailForm.vue'

const provider = new FindEncounterByIdQueryHandlerProvider()
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
      />
      <AddEncounterDetailForm
        :encounter-ulid="encounterUlid"
        @monster:added="loadEncounter"
      />
    </template>
    <v-card-actions class="justify-space-between">
      <v-btn
        color="primary"
        prepend-icon="mdi-arrow-left"
        :active="false"
        :to="{ name: 'Encounter' }"
      >
        Cancel
      </v-btn>
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-plus"
      >
        Add
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
