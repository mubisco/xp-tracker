<script lang="ts" setup>
import { ref, watch, onMounted } from 'vue'

const players = ref([])
const props = defineProps({
  partyUlid: { type: String, required: true },
  partyName: { type: String, required: true }
})

onMounted(async () => fetchPlayers())

const fetchPlayers = async () => {
  const url = 'http://localhost:5000/api/party/' + props.partyUlid + '/characters'
  const response = await fetch(url, {
    method: 'GET',
    headers: { 'Content-Type': 'application/json' }
  })
  players.value = await response.json()
}

watch(() => props.partyUlid, async () => fetchPlayers())

</script>
<template>
  <v-card
    class="mt-3 pb-4"
  >
    <template #title>
      Characters on {{ partyName }} party
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
              {{ player.xp }} / {{ player.next }}
            </td>
            <td class="text-right">
              {{ player.level }} ({{ player.level + 1 }})
            </td>
            <td class="text-right">
              <v-btn
                icon="mdi-delete"
                color="red"
                variant="plain"
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
