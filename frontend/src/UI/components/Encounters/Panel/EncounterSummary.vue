<script lang="ts" setup>
defineProps({
  totalXp: { type: Number, required: true },
  totalCr: { type: Number, required: true },
  totalMonsters: { type: Number, required: true }
})
</script>

<template>
  <tfoot>
    <tr>
      <th
        class="text-right"
        colspan="3"
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
        colspan="3"
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
        colspan="3"
      >
        Total Points
      </th>
      <td
        class="text-right"
      >
        {{ totalCr }}
      </td>
    </tr>
  </tfoot>
</template>
