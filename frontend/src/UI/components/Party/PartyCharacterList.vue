<script lang="ts" setup>
import { watch, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useCharacterStore } from '@/UI/store/characters'
import { usePartyStore } from '@/UI/store/parties'

const partyStore = usePartyStore()
const characterStore = useCharacterStore()
const { characters } = storeToRefs(characterStore)
const { activePartyUlid, activeParty } = storeToRefs(partyStore)

onMounted(async () => fetchPlayers())
watch(activePartyUlid, () => { fetchPlayers() })

const fetchPlayers = async () => {
  characterStore.loadCharacters(activePartyUlid.value)
}

const onDeleteCharacterButtonClicked = async (characterUlid: string) => {
  console.log('onDeleteCharacterButtonClicked', characterUlid, activePartyUlid.value)
  await characterStore.deleteCharacter(activePartyUlid.value, characterUlid)
}
</script>
<template>
  <v-card class="mt-3 pb-4">
    <template #title>
      Characters on {{ activeParty.partyName }} party
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
                @click="onDeleteCharacterButtonClicked(character.ulid)"
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
    :to="{ name: 'AddCharacter', params: { partyUlid: activePartyUlid } }"
  />
</template>
