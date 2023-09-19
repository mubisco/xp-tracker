<script lang="ts" setup>
import { AddCharacterCommand } from '@/Application/Character/Command/AddCharacterCommand'
import { AddCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/AddCharacterCommandHandlerProvider'
import { ref } from 'vue'
const name = ref('')
const actualXp = ref(0)
const maxHp = ref(0)

const useCaseProvider = new AddCharacterCommandHandlerProvider()
const useCase = useCaseProvider.provide()

const addNewCharacter = async () => {
  const command = new AddCharacterCommand(name.value, actualXp.value, maxHp.value)
  await useCase.handle(command)
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
              label="Name"
              type="text"
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
        @click="addNewCharacter"
      >
        Add
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
