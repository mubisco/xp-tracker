<script lang="ts" setup>
import { ref } from 'vue'
import PartyCharacterList from '@/UI/components/Party/PartyCharacterList.vue'
import PartiesList from '@/UI/components/Party/PartiesList.vue'

const selectedPartyUlid = ref('')
const onPartySelected = (partyUlid: string) => {
  selectedPartyUlid.value = partyUlid
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
    />
  </v-container>
</template>
