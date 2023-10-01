<script lang="ts" setup>

import { ref } from 'vue'
const currentMonsters = ref([
  {
    name: 'Shadow Dancer',
    cr: 7,
    xp: 2900
  },
  {
    name: 'Shadow Dancer',
    cr: 7,
    xp: 2900
  }
])

</script>

<template>
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
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(monster, index) in currentMonsters"
        :key="index"
      >
        <td>{{ monster.name }}</td>
        <td class="text-right">
          {{ monster.cr }}
        </td>
        <td class="text-right">
          {{ monster.xp }}
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th
          class="text-right"
          colspan="2"
        >
          Total Monsters
        </th>
        <td
          class="text-right"
        >
          2
        </td>
      </tr>
      <tr>
        <th
          class="text-right"
          colspan="2"
        >
          Total XP
        </th>
        <td
          class="text-right"
        >
          5800
        </td>
      </tr>
      <tr>
        <th
          class="text-right"
          colspan="2"
        >
          Total Points
        </th>
        <td
          class="text-right"
        >
          7200
        </td>
      </tr>
    </tfoot>
  </v-table>
</template>
