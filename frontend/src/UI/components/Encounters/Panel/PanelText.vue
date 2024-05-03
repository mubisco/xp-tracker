<script lang="ts" setup>
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import MonstersList from './MonstersList.vue'
import EncounterSummary from './EncounterSummary.vue'
import FinishEncounterButton from './FinishEncounterButton.vue'

defineProps<{
  ulid: string,
  name: string,
  status: string,
  totalXp: number,
  totalCr: number,
  monsters: MonsterDto[]
}>()

</script>
<template>
  <v-expansion-panel-text>
    <v-table density="compact">
      <thead>
        <tr>
          <th class="text-left">
            Name
          </th>
          <th class="text-right">
            CR
          </th>
          <th class="text-right">
            XP
          </th>
          <th />
        </tr>
      </thead>
      <MonstersList
        :monsters="monsters"
      />
      <EncounterSummary
        :total-monsters="monsters.length"
        :total-xp="totalXp"
        :total-cr="totalCr"
      />
    </v-table>
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
