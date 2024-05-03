<script lang="ts" setup>
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import { computed } from 'vue'

const props = defineProps<{ monsters: MonsterDto[], showDeleteButton: boolean | null | undefined }>()
defineEmits<{(e: 'monster:deleted', payload: MonsterDto): void}>()

const totalMonsters = computed((): number => {
  return props.monsters.length
})

const totalXp = computed((): number => {
  let sum = 0
  props.monsters.forEach((monster): void => {
    // @ts-ignore
    sum = sum + parseInt(monster.xp, 10)
  })
  return sum
})

const totalChallengePoints = computed((): number => {
  return totalXp.value * getMultiplier(totalMonsters.value)
})

const getMultiplier = (totalMonsters: number): number => {
  if (totalMonsters > 14) {
    return 4
  }
  if (totalMonsters > 10) {
    return 3
  }
  if (totalMonsters > 6) {
    return 2.5
  }
  if (totalMonsters > 2) {
    return 2
  }
  if (totalMonsters > 1) {
    return 1.5
  }
  return 1
}

const spanColumns = computed((): number => {
  return props.showDeleteButton ? 3 : 2
})

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
        <th v-if="showDeleteButton" />
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(monster, index) in monsters"
        :key="index"
      >
        <td>{{ monster.name }}</td>
        <td class="text-right">
          {{ monster.cr }}
        </td>
        <td class="text-right">
          {{ monster.xp }}
        </td>
        <td
          v-if="showDeleteButton"
          class="text-right"
        >
          <v-btn
            variant="plain"
            size="xs"
            color="error"
            icon="mdi-delete"
            @click="$emit('monster:deleted', monster)"
          />
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th
          class="text-right"
          :colspan="spanColumns"
        >
          Total Monsters
        </th>
        <td
          class="text-right"
        >
          {{ totalMonsters }}
        </td>
      </tr>
      <tr>
        <th
          class="text-right"
          :colspan="spanColumns"
        >
          Total XP
        </th>
        <td
          class="text-right"
        >
          {{ totalXp }}
        </td>
      </tr>
      <tr>
        <th
          class="text-right"
          :colspan="spanColumns"
        >
          Total Points
        </th>
        <td
          class="text-right"
        >
          {{ totalChallengePoints }}
        </td>
      </tr>
    </tfoot>
  </v-table>
</template>
