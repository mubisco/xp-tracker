<script lang="ts" setup>
import { ref, onMounted } from 'vue'

const players = ref([])
defineProps({
  partyUlid: { type: String, required: true }
})

onMounted(async () => fetchPlayers())

const fetchPlayers = async () => {
}

</script>
<template>
  <v-card
    class="mt-3 pb-4"
  >
    <template #title>
      Characters From Party
    </template>
    <template #text>
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-right">
              XP
            </th>
            <th class="text-right">
              Level
            </th>
            <th class="text-right">
              Actions
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
              {{ player.level }} ({{ player.level + 1 }})
            </td>
            <td class="text-right">
              <v-btn
                icon="mdi-delete"
                color="red"
                variant="plain"
                @click="onDeleteCharacterClicked(player.ulid, player.name)"
              />
            </td>
          </tr>
        </tbody>
      </v-table>
    </template>
  </v-card>
  <v-fab
    class="me-4"
    icon="mdi-account-plus"
    color="primary"
    location="top end"
    absolute
    offset
    :to="{ name: 'AddCharacter', params: { partyUlid: partyUlid } }"
  />
</template>
