<script lang="ts" setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePartyStore } from '@/UI/store/parties'

const partyStore = usePartyStore()
const { allParties, areParties } = storeToRefs(partyStore)

onMounted(async () => {
  await partyStore.loadParties()
})

const emit = defineEmits<{(e: 'party:selected', payload: { partyUlid: string, partyName: string }): void}>()

const onCheckPartyButtonClicked = (partyUlid: string, partyName: string) => {
  partyStore.selectParty(partyUlid)
  const payload = { partyUlid, partyName }
  emit('party:selected', payload)
}

</script>
<template>
  <v-card>
    <template #title>
      Parties
    </template>
    <template #text>
      <v-alert
        v-if="areParties"
        class="mb-6"
        text="Currently there are no parties available. If you want, you may create one, clicking on the button on the top right corner"
        title="No Parties"
        type="info"
      />
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-right">
              Total Characters
            </th>
            <th class="text-right">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(party, index) in allParties"
            :key="index"
          >
            <td>{{ party.partyName }}</td>
            <td class="text-right">
              {{ party.partyCharacters }}
            </td>
            <td class="text-right">
              <v-btn
                icon="mdi-eye"
                variant="plain"
                @click="onCheckPartyButtonClicked(party.partyUlid, party.partyName)"
              />
              <!-- <v-btn -->
              <!--   icon="mdi-delete" -->
              <!--   color="red" -->
              <!--   variant="plain" -->
              <!-- /> -->
            </td>
          </tr>
        </tbody>
      </v-table>
    </template>
  </v-card>
</template>
