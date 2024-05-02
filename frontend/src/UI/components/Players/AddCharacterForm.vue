<script lang="ts" setup>
import { AddCharacterToPartyCommand } from '@/Application/Party/Command/Character/AddCharacterToPartyCommand'
import { AddCharacterToPartyCommandHandlerProvider } from '@/Infrastructure/Party/Provider/AddCharacterToPartyCommandHandlerProvider'
import { useSnackbarStore } from '@/UI/store/snackbar'
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const name = ref('')
const actualXp = ref(0)
const isValid = computed((): boolean => name.value !== '')
const rules = ref({ nameNotEmpty: (value: string) => !!value || 'Name must not be empty' })

const useCaseProvider = new AddCharacterToPartyCommandHandlerProvider()
const route = useRoute()
const router = useRouter()

const snackbarStore = useSnackbarStore()

const addNewCharacter = async () => {
  const partyUlid = route.params.partyUlid ?? ''
  const useCase = useCaseProvider.provide()
  const command = new AddCharacterToPartyCommand(partyUlid, name.value, actualXp.value)
  await useCase.handle(command)
  snackbarStore.addMessage('Character added successfully to party!!', 'success')
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
