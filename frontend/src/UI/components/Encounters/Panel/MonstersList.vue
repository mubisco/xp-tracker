<script lang="ts" setup>
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import { storeToRefs } from 'pinia'
import { useEncountersStore } from '@/UI/store/encounters'
import { usePartyStore } from '@/UI/store/parties'

const encountersStore = useEncountersStore()
const partyStore = usePartyStore()
const { activePartyUlid } = storeToRefs(partyStore)

const props = defineProps<{ monsters: MonsterDto[], status: string, ulid: string }>()

const onDeleteMonsterButtonClicked = async (name: string, challengeRating: string) => {
  await encountersStore.removeMonster(
    activePartyUlid.value,
    props.ulid,
    name,
    challengeRating
  )
}
</script>

<template>
  <tbody>
    <tr
      v-for="(monster, index) in monsters"
      :key="index"
    >
      <td>{{ monster.name }}</td>
      <td class="text-right">
        {{ monster.challengeRating }}
      </td>
      <td class="text-right">
        {{ monster.experiencePoints }}
      </td>
      <td
        class="text-right"
      >
        <v-btn
          v-if="status !== 'RESOLVED'"
          variant="plain"
          size="xs"
          color="error"
          icon="mdi-delete"
          @click="onDeleteMonsterButtonClicked(monster.name, monster.challengeRating)"
        />
      </td>
    </tr>
  </tbody>
</template>
