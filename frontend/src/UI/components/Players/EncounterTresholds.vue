<script lang="ts" setup>
import { ref } from 'vue'

const tresholds = ref({
  EASY: 2750,
  MEDIUM: 5500,
  HARD: 8000,
  DEADLY: 12000

})
</script>
<template>
  <v-card>
    <template #title>
      Encounters Tresholds
    </template>
    <template #text>
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-right">
              Easy
            </th>
            <th class="text-right">
              Medium
            </th>
            <th class="text-right">
              Hard
            </th>
            <th class="text-right">
              Deadly
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td
              v-for="(treshold, key) in tresholds"
              :key="key"
              class="text-right"
            >
              {{ treshold }}
            </td>
          </tr>
        </tbody>
      </v-table>
    </template>
  </v-card>
</template>
