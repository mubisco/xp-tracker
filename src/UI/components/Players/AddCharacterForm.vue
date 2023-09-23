<script lang="ts" setup>
import { AddCharacterCommand } from '@/Application/Character/Command/AddCharacterCommand'
import { AddCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/AddCharacterCommandHandlerProvider'
import { useSnackbarStore } from '@/UI/store/snackbar'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const name = ref('')
const actualXp = ref(0)
const maxHp = ref(0)
const isValid = computed((): boolean => name.value !== '' && maxHp.value > 0)

const rules = ref({
  nameNotEmpty: (value: string) => !!value || 'Name must not be empty',
  hpNotZero: (value: number) => value > 0 || 'Hitpoints must be above 0'
})

const useCaseProvider = new AddCharacterCommandHandlerProvider()
const useCase = useCaseProvider.provide()
const router = useRouter()

const snackbarStore = useSnackbarStore()

const addNewCharacter = async () => {
  const command = new AddCharacterCommand(name.value, actualXp.value, maxHp.value)
  await useCase.handle(command)
  snackbarStore.addMessage('Character added successfully!!', 'success')
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
          <v-col cols="12">
            <v-text-field
              v-model="name"
              label="Character name"
              type="text"
              :rules="[rules.nameNotEmpty]"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="6">
            <v-text-field
              v-model="actualXp"
              label="Experience Points"
              type="number"
            />
          </v-col>
          <v-col cols="6">
            <v-text-field
              v-model="maxHp"
              label="Hit Points"
              type="number"
              :rules="[rules.hpNotZero]"
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
