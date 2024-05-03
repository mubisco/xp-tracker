<script lang="ts" setup>
import { useSnackbarStore } from '@/UI/store/snackbar'
import { useCharacterStore } from '@/UI/store/characters'
import { usePartyStore } from '@/UI/store/parties'
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const route = useRoute()
const router = useRouter()
const characterStore = useCharacterStore()
const snackbarStore = useSnackbarStore()
const partyStore = usePartyStore()

const name = ref('')
const actualXp = ref(0)
const isValid = computed((): boolean => name.value !== '')
const rules = ref({ nameNotEmpty: (value: string) => !!value || 'Name must not be empty' })

const addNewCharacter = async () => {
  const partyUlid = route.params.partyUlid ?? ''
  await characterStore.createCharacter(partyUlid, name.value, actualXp.value)
  snackbarStore.addMessage('Character added successfully to party!!', 'success')
  setTimeout(() => { partyStore.loadParties() }, 750)
  router.replace({ name: 'Home' })
}
</script>

<template>
  <v-card>
    <template #title>
      Add Character
    </template>
    <template #text>
      <v-form>
        <v-row>
          <v-col cols="6">
            <v-text-field
              v-model="name"
              label="Character name"
              type="text"
              :rules="[rules.nameNotEmpty]"
            />
          </v-col>
          <v-col cols="6">
            <v-text-field
              v-model="actualXp"
              label="Experience Points"
              type="number"
            />
          </v-col>
        </v-row>
      </v-form>
    </template>
    <v-card-actions class="justify-space-between">
      <v-btn
        color="primary"
        prepend-icon="mdi-arrow-left"
        :active="false"
        :to="{ name: 'Home' }"
      >
        Cancel
      </v-btn>
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-plus"
        :disabled="!isValid"
        @click="addNewCharacter"
      >
        Add
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
