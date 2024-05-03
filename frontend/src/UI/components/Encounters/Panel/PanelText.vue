<script lang="ts" setup>
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import MonstersList from './MonstersList.vue'
import EncounterSummary from './EncounterSummary.vue'
import EncounterActions from './EncounterActions.vue'

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
        :status="status"
        :ulid="ulid"
      />
      <EncounterSummary
        :total-monsters="monsters.length"
        :total-xp="totalXp"
        :total-cr="totalCr"
      />
    </v-table>
    <EncounterActions
      :ulid="ulid"
      :status="status"
    />
  </v-expansion-panel-text>
</template>
