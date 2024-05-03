<script lang="ts" setup>
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'

defineProps<{ monsters: MonsterDto[] }>()
defineEmits<{(e: 'monster:deleted', payload: MonsterDto): void}>()
</script>

<template>
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
</template>
