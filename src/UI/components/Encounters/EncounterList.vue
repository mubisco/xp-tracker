<script lang="ts" setup>
import { ref } from 'vue'
import LevelTag from '@/UI/components/Encounters/LevelTag.vue'

const encounters = ref([
  {
    id: 'abc8882e-ae2a-41ad-ab70-8115b2a3fe90',
    name: 'Encuentro 1',
    level: 'EASY',
    totalXp: 5800,
    totalPoints: 8700,
    monsters: [
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
    ]
  },
  {
    id: '8ab87248-cf76-4917-afc2-db0ec645a009',
    name: 'Encuentro 1',
    level: 'MEDIUM',
    totalXp: 5800,
    totalPoints: 8700,
    monsters: [
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
    ]
  },
  {
    id: '05854b04-27aa-4976-ad29-ced3b8ba4669',
    name: 'Encuentro 1',
    level: 'HARD',
    totalXp: 5800,
    totalPoints: 8700,
    monsters: [
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
    ]
  },
  {
    id: '393a0464-9f27-4950-acc0-0398a1b3560e',
    name: 'Encuentro 4',
    level: 'DEADLY',
    totalXp: 5800,
    totalPoints: 8700,
    monsters: [
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
    ]
  }
])
</script>
<template>
  <v-expansion-panels>
    <v-expansion-panel
      v-for="encounter in encounters"
      :key="encounter.id"
    >
      <v-expansion-panel-title>
        <div class="d-flex w-100">
          <div class="flex-grow-1">
            {{ encounter.name }}
          </div>
          <LevelTag :level="encounter.level" />
        </div>
      </v-expansion-panel-title>
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
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(monster, index) in encounter.monsters"
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
                {{ encounter.monsters.length }}
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
                {{ encounter.totalXp }}
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
                {{ encounter.totalPoints }}
              </td>
            </tr>
          </tfoot>
        </v-table>
      </v-expansion-panel-text>
    </v-expansion-panel>
  </v-expansion-panels>
</template>
