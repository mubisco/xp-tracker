<script lang="ts" setup>
import { ref } from 'vue'
import CharacterMainList from '@/UI/components/Players/CharacterMainList.vue'
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
    <h1>Current Party: {{ selectedPartyUlid }}</h1>
  </v-container>
</template>
