<script lang="ts" setup>
import { watch, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useCharacterStore } from '@/UI/store/characters'

const props = defineProps({
  partyUlid: { type: String, required: true },
  partyName: { type: String, required: true }
})
const characterStore = useCharacterStore()
const { characters } = storeToRefs(characterStore)

onMounted(async () => fetchPlayers())

const fetchPlayers = async () => {
  characterStore.loadCharacters(props.partyUlid)
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
            v-for="(character, index) in characters"
            :key="index"
          >
            <td>{{ character.name }}</td>
            <td class="text-right">
              {{ character.xp }} / {{ character.next }}
            </td>
            <td class="text-right">
              {{ character.level }} ({{ character.level + 1 }})
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
