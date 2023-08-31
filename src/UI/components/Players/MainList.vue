<script lang="ts" setup>
import { ref } from 'vue'

const players = ref([
  {
    name: 'Carlitos',
    xp: 24350,
    professions: [
      { name: 'Warlock', level: 7 }
    ],
    currentHp: 25,
    maxHp: 25,
    nextLevel: 35000,
    level: 7,
    effectiveLevel: 9
  },
  {
    name: 'Alvarete',
    xp: 24350,
    professions: [
      { name: 'Paladin', level: 3 },
      { name: 'Warlock', level: 4 }
    ],
    currentHp: 25,
    maxHp: 32,
    nextLevel: 35000,
    level: 7,
    effectiveLevel: 9
  }
])
</script>
<template>
  <v-card>
    <template #title>
      Characters
    </template>
    <template #text>
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-right">
              HP
            </th>
            <th class="text-right">
              XP
            </th>
            <th class="text-right">
              Level
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(player, index) in players"
            :key="index"
          >
            <td>{{ player.name }}</td>
            <td class="text-right">
              {{ player.currentHp }} / {{ player.maxHp }}
            </td>
            <td class="text-right">
              {{ player.xp }} / {{ player.nextLevel }}
            </td>
            <td class="text-right">
              {{ player.level }} ({{ player.effectiveLevel }})
            </td>
          </tr>
        </tbody>
      </v-table>
    </template>
  </v-card>
</template>
