<script lang="ts" setup>
import { AddEncounterCommand } from '@/Application/Encounter/Command/AddEncounterCommand'
import { AddEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/AddEncounterCommandHandlerProvider'
import { useSnackbarStore } from '@/UI/store/snackbar'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

const encounterName = ref('')
const rules = ref({
  nameNotEmpty: (value: string) => !!value || 'Encounter name must not be empty'
})
const isValid = computed((): boolean => encounterName.value !== '')
const router = useRouter()
const snackbarStore = useSnackbarStore()
const provider = new AddEncounterCommandHandlerProvider()

const onCreateCharacterClicked = async () => {
  const command = new AddEncounterCommand(encounterName.value)
  const useCase = provider.provide()
  await useCase.handle(command)
  snackbarStore.addMessage('Encounter created successfully!!', 'success')
  router.replace({ name: 'Encounter' })
}
</script>

<template>
  <v-card>
    <template #title>
      Add Encounter
    </template>
    <template #text>
      <v-form>
        <v-row>
          <v-col
            cols="12"
          >
            <v-text-field
              v-model="encounterName"
              label="Encounter Name"
              density="compact"
              type="text"
              :rules="[rules.nameNotEmpty]"
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
        :to="{ name: 'Encounter' }"
      >
        Cancel
      </v-btn>
      <v-btn
        variant="elevated"
        color="primary"
        :disabled="!isValid"
        prepend-icon="mdi-plus"
        @click="onCreateCharacterClicked"
      >
        Create
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
