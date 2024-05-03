<script lang="ts" setup>
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import EncounterDetails from './EncounterDetails.vue'
import FinishEncounterButton from './FinishEncounterButton.vue'

defineProps({
  ulid: { type: String, required: true },
  name: { type: String, required: true },
  status: { type: String, required: true },
  monsters: { type: Array as PropType<MonsterDto[]>, required: true }
})

</script>
<template>
  <v-expansion-panel-text>
    <EncounterDetails
      :show-delete-button="false"
      :monsters="monsters"
    />
    <div class="mt-4 d-flex justify-space-between">
      <v-btn
        variant="outlined"
        color="error"
        prepend-icon="mdi-delete"
      >
        Remove
      </v-btn>
      <v-btn
        variant="outlined"
        color="primary"
        prepend-icon="mdi-pencil"
        :disabled="status === 'DONE'"
        :to="{ name: 'EditEncounter', params: { encounterId: ulid } }"
      >
        Edit
      </v-btn>
      <FinishEncounterButton
        :encounter-ulid="ulid"
        :encounter-status="status"
      />
    </div>
  </v-expansion-panel-text>
</template>
