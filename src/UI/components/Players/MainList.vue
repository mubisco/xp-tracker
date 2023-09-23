<script lang="ts" setup>
import { FetchCharactersQuery } from '@/Application/Character/Query/FetchCharactersQuery'
import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { FetchCharactersQueryHandlerProvider } from '@/Infrastructure/Character/Provider/FetchCharactersQueryHandlerProvider'
import { onMounted, ref } from 'vue'

const players = ref<CharacterDto[]>([])
const useCaseProvider = new FetchCharactersQueryHandlerProvider()
const useCase = useCaseProvider.provide()

onMounted(async () => {
  players.value = await useCase.invoke(new FetchCharactersQuery())
})

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
          </tr>
        </tbody>
      </v-table>
    </template>
  </v-card>
</template>
