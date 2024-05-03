<script lang="ts" setup>
import { ref } from 'vue'
import PartyCharacterList from '@/UI/components/Party/PartyCharacterList.vue'
import PartiesList from '@/UI/components/Party/PartiesList.vue'

const selectedPartyUlid = ref('')
const selectedPartyName = ref('')

const onPartySelected = (payload: { partyUlid: string, partyName: string }) => {
  selectedPartyUlid.value = payload.partyUlid
  selectedPartyName.value = payload.partyName
}
</script>
<template>
  <v-container>
    <PartiesList
      @party:selected="onPartySelected"
    />
    <PartyCharacterList
      v-if="selectedPartyUlid !== ''"
      :party-ulid="selectedPartyUlid"
      :party-name="selectedPartyName"
    />
  </v-container>
</template>
