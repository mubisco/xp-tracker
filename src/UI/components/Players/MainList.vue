<script lang="ts" setup>
import { DeleteCharacterCommand } from '@/Application/Character/Command/DeleteCharacterCommand';
import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler';
import { FetchCharactersQuery } from '@/Application/Character/Query/FetchCharactersQuery'
import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository';
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor';
import { FetchCharactersQueryHandlerProvider } from '@/Infrastructure/Character/Provider/FetchCharactersQueryHandlerProvider'
import { onMounted, ref } from 'vue'

const players = ref<CharacterDto[]>([])
const useCaseProvider = new FetchCharactersQueryHandlerProvider()
const useCase = useCaseProvider.provide()
const showDeleteConfirmationDialog = ref(false)
const characterToDelete = ref('')
const characterUlidToDelete = ref('')
const showDeleteConfirmationAlert = ref(false)

onMounted(async () => {
  players.value = await useCase.invoke(new FetchCharactersQuery())
})

const onDeleteCharacterClicked = (characterUlid: string, characterName: string): void => {
  characterToDelete.value = characterName
  characterUlidToDelete.value = characterUlid
  showDeleteConfirmationDialog.value = true
}

const onDeleteConfirmationClicked = async () => {
  const handler = new DeleteCharacterCommandHandler(
    new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
  )
  const command = new DeleteCharacterCommand(characterUlidToDelete.value)
  await handler.handle(command)
  showDeleteConfirmationDialog.value = false
  showDeleteConfirmationAlert.value = true
  players.value = await useCase.invoke(new FetchCharactersQuery())
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
      <v-dialog
        v-model="showDeleteConfirmationDialog"
        width="auto"
      >
        <v-card>
          <v-card-text>
            Are you sure to delete this character: {{ characterToDelete }}?
          </v-card-text>
          <v-card-actions>
            <v-btn
              variant="elevated"
              color="error"
              block
              @click="onDeleteConfirmationClicked()"
            >
              Confirm Deletion
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </template>
  </v-card>
  <v-alert
    v-model="showDeleteConfirmationAlert"
    type="success"
    title="Character deleted!!!"
    :text="`${characterToDelete} character deleted succesfully!!`"
  />
</template>
