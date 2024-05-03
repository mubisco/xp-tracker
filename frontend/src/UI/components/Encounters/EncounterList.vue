<script lang="ts" setup>
import PanelTitle from './Panel/PanelTitle.vue'
import PanelText from './Panel/PanelText.vue'
import RemoveEncounterDialog from './RemoveEncounterDialog.vue'

import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePartyStore } from '@/UI/store/parties'
import { useEncountersStore } from '@/UI/store/encounters'

const partyStore = usePartyStore()
const encountersStore = useEncountersStore()
const { activeParty } = storeToRefs(partyStore)
const { currentEncounters } = storeToRefs(encountersStore)

const encounterToDelete = ref('')
const showDeleteDialog = ref(false)

const loadEncounters = async () => {
  if (activeParty) {
    await encountersStore.loadEncounters(activeParty.value.partyUlid)
  }
}

onMounted(loadEncounters)

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
    <v-alert
      v-if="currentEncounters.length === 0"
      class="mb-6"
      text="No encounters defined for this party. If you want to create one, click on the button on the top right corner"
      title="No encounters for this party"
      type="info"
    />
    <v-expansion-panels>
      <v-expansion-panel
        v-for="encounter in currentEncounters"
        :key="encounter.ulid"
      >
        <PanelTitle
          :name="encounter.name"
          :level="encounter.level"
          :status="encounter.status"
        />
        <PanelText
          :ulid="encounter.ulid"
          :name="encounter.name"
          :status="encounter.status"
          :total-xp="encounter.totalXp"
          :total-cr="encounter.totalCrPoints"
          :monsters="encounter.monsters"
        />
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
