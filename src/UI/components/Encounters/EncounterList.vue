<script lang="ts" setup>
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { AllEncountersQueryHandlerProvider } from '@/Infrastructure/Encounter/Provider/AllEncountersQueryHandlerProvider';
import EncounterDetails from './EncounterDetails.vue';
// import LevelTag from '@/UI/components/Encounters/LevelTag.vue'
import { ref, onMounted } from 'vue'

const provider = new AllEncountersQueryHandlerProvider()

const encounters = ref<EncounterDto[]>([])

onMounted(async () => {
  const handler = provider.provide()
  const result = await handler.handle()
  encounters.value = result
})

</script>
<template>
  <v-expansion-panels>
    <v-expansion-panel
      v-for="encounter in encounters"
      :key="encounter.ulid"
    >
      <v-expansion-panel-title>
        <div class="d-flex w-100">
          <div class="flex-grow-1">
            {{ encounter.name }}
          </div>
          <!-- <LevelTag :level="encounter.level" /> -->
        </div>
      </v-expansion-panel-title>
      <v-expansion-panel-text>
        <EncounterDetails
          :monsters="encounter.monsters"
        />
        <div class="mt-4 d-flex justify-space-between">
          <v-btn
            variant="outlined"
            color="primary"
            prepend-icon="mdi-pencil"
            :to="{ name: 'EditEncounter', params: { encounterId: encounter.ulid } }"
          >
            Edit
          </v-btn>
          <v-btn
            variant="elevated"
            color="primary"
            prepend-icon="mdi-content-save"
          >
            Add XP points to characters
          </v-btn>
        </div>
      </v-expansion-panel-text>
    </v-expansion-panel>
  </v-expansion-panels>
</template>
