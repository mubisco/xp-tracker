<script lang="ts" setup>
import PartyCharacterList from '@/UI/components/Party/PartyCharacterList.vue'
import PartiesList from '@/UI/components/Party/PartiesList.vue'
import { usePartyStore } from '@/UI/store/parties'
import { storeToRefs } from 'pinia'

const partyStore = usePartyStore()
const { activePartyUlid } = storeToRefs(partyStore)

</script>
<template>
  <v-container>
    <PartiesList />
    <PartyCharacterList
      v-if="activePartyUlid !== ''"
    />
  </v-container>
</template>
