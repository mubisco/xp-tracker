<script lang="ts" setup>
import { FetchCharactersQuery } from '@/Application/Character/Query/FetchCharactersQuery'
import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { FetchCharactersQueryHandlerProvider } from '@/Infrastructure/Character/Provider/FetchCharactersQueryHandlerProvider'
import DeleteCharacterDialog from './DeleteCharacterDialog.vue'
import { onMounted, ref, watch } from 'vue'

const players = ref<CharacterDto[]>([])
const useCaseProvider = new FetchCharactersQueryHandlerProvider()
const showDeleteConfirmationDialog = ref(false)
const characterToDelete = ref('')
const characterUlidToDelete = ref('')

onMounted(async () => updatePlayers())

watch(showDeleteConfirmationDialog, async () => {
  if (showDeleteConfirmationDialog.value === false) {
    updatePlayers()
  }
})

const updatePlayers = async () => {
  const useCase = useCaseProvider.provide()
  players.value = await useCase.invoke(new FetchCharactersQuery())
}

const onDeleteCharacterClicked = (characterUlid: string, characterName: string): void => {
  characterToDelete.value = characterName
  characterUlidToDelete.value = characterUlid
  showDeleteConfirmationDialog.value = true
}

</script>
<template>
  <v-card>
    <template #title>
      Characters
    </template>
    <template #text>
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-right">
              HP
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
      <DeleteCharacterDialog
        v-model="showDeleteConfirmationDialog"
        :character-name="characterToDelete"
        :character-ulid="characterUlidToDelete"
      />
    </template>
  </v-card>
</template>
