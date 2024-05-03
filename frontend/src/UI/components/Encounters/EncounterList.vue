<script lang="ts" setup>
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { AllEncountersQueryHandlerProvider } from '@/Infrastructure/Encounter/Provider/AllEncountersQueryHandlerProvider'
import EncounterDetails from './EncounterDetails.vue'
import RemoveEncounterDialog from './RemoveEncounterDialog.vue'
import LevelTag from '@/UI/components/Encounters/LevelTag.vue'
import FinishEncounterButton from './FinishEncounterButton.vue'

import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePartyStore } from '@/UI/store/parties'

const partyStore = usePartyStore()
const { activeParty } = storeToRefs(partyStore)

const provider = new AllEncountersQueryHandlerProvider()

const encounters = ref<EncounterDto[]>([])
const encounterToDelete = ref('')
const showDeleteDialog = ref(false)

const loadEncounters = async () => {
  const handler = provider.provide()
  const result = await handler.handle()
  encounters.value = result
}

onMounted(loadEncounters)

const onRemoveEncounterButtonClicked = async (encounterUlid: string): Promise<void> => {
  encounterToDelete.value = encounterUlid
  showDeleteDialog.value = true
}

const onEncounterDeleteConfirmed = async (): Promise<void> => {
  encounterToDelete.value = ''
  await loadEncounters()
}

</script>
<template>
  <div
    v-if="activeParty"
  >
    <h1 class="text-h5 mb-3">
      Selected party: {{ activeParty.partyName }}
    </h1>
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
            <LevelTag
              :level="encounter.level"
              :status="encounter.status"
            />
          </div>
        </v-expansion-panel-title>
        <v-expansion-panel-text>
          <EncounterDetails
            :show-delete-button="false"
            :monsters="encounter.monsters"
          />
          <div class="mt-4 d-flex justify-space-between">
            <v-btn
              variant="outlined"
              color="error"
              prepend-icon="mdi-delete"
              @click="onRemoveEncounterButtonClicked(encounter.ulid)"
            >
              Remove
            </v-btn>
            <v-btn
              variant="outlined"
              color="primary"
              prepend-icon="mdi-pencil"
              :disabled="encounter.status === 'DONE'"
              :to="{ name: 'EditEncounter', params: { encounterId: encounter.ulid } }"
            >
              Edit
            </v-btn>
            <FinishEncounterButton
              :encounter-ulid="encounter.ulid"
              :encounter-status="encounter.status"
            />
          </div>
        </v-expansion-panel-text>
      </v-expansion-panel>
    </v-expansion-panels>
    <RemoveEncounterDialog
      v-model="showDeleteDialog"
      :encounter-ulid="encounterToDelete"
      @update:model-value="onEncounterDeleteConfirmed"
    />
  </div>
  <v-alert
    v-else
    text="You cannot set or view encounters if there is no party selected. Please come back to Players section and select one encounter"
    title="No Party selected"
    type="warning"
  />

</template>
