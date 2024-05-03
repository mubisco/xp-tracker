<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useEncountersStore } from '@/UI/store/encounters'
import { usePartyStore } from '@/UI/store/parties'

const encountersStore = useEncountersStore()
const partyStore = usePartyStore()
const { activePartyUlid } = storeToRefs(partyStore)

const props = defineProps({ encounterUlid: { type: String, required: true } })

const crOptions = ['0', '1/8', '1/4', '1/2', '1', '2', '3', '4', '5', '6', '7', '8',
  '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22',
  '23', '24', '25', '26', '30'
]
const name = ref('')
const cr = ref('')

const isValid = computed((): boolean => name.value !== '' && cr.value !== '')

const onAddMonsterButtonClicked = async () => {
  await encountersStore.addMonster(
    activePartyUlid.value,
    props.encounterUlid,
    name.value,
    cr.value
  )
  name.value = ''
  cr.value = ''
}
</script>

<template>
  <v-sheet
    class="pa-3"
    border
  >
    <span class="text-caption pb-2">Add monster</span>
    <v-form>
      <v-row>
        <v-col
          cols="6"
        >
          <v-text-field
            v-model="name"
            label="Name"
            density="compact"
            type="text"
          />
        </v-col>
        <v-col
          cols="3"
        >
          <v-select
            v-model="cr"
            label="CR"
            density="compact"
            :items="crOptions"
          />
        </v-col>
        <v-col
          cols="3"
        >
          <v-btn
            variant="outlined"
            color="primary"
            :disabled="!isValid"
            prepend-icon="mdi-plus"
            @click="onAddMonsterButtonClicked"
          >
            Add
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-sheet>
</template>
