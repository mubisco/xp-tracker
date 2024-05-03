<script lang="ts" setup>
import AddMonsterForm from './AddMonsterForm.vue'

import { storeToRefs } from 'pinia'
import { useEncountersStore } from '@/UI/store/encounters'
import { usePartyStore } from '@/UI/store/parties'

const encountersStore = useEncountersStore()
const partyStore = usePartyStore()
const { activePartyUlid } = storeToRefs(partyStore)

const props =defineProps<{
  ulid: string,
  status: string,
}>()

const onResolveEncounterButtonClicked = async () => {
  await encountersStore.resolveEncounter(activePartyUlid.value, props.ulid)
}

</script>
<template>
  <div>
    <div class="mt-4">
      <AddMonsterForm
        v-if="status !== 'RESOLVED'"
        :encounter-ulid="ulid"
      />
    </div>
    <div class="mt-4 d-flex justify-space-between">
      <v-btn
        variant="outlined"
        color="error"
        prepend-icon="mdi-delete"
      >
        Delete
      </v-btn>
      <v-btn
        v-if="status !== 'RESOLVED'"
        variant="elevated"
        color="primary"
        prepend-icon="mdi-content-save"
        @click="onResolveEncounterButtonClicked"
      >
        Resolve
      </v-btn>
    </div>
  </div>
</template>
