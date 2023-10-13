<script lang="ts" setup>
import { AddMonsterToEncounterCommand } from '@/Application/Encounter/Command/AddMonsterToEncounterCommand'
import { AddMonsterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/AddMonsterCommandHandlerProvider'
import { ref } from 'vue'

const props = defineProps({
  encounterUlid: { type: String, required: true }
})
const emit = defineEmits(['monster:added'])

const name = ref('')
const xp = ref(0)
const cr = ref('')

const provider = new AddMonsterCommandHandlerProvider()

const addRow = async () => {
  const handler = provider.provide()
  const command = new AddMonsterToEncounterCommand(
    props.encounterUlid,
    name.value,
    // @ts-ignore
    parseInt(xp.value, 10),
    cr.value
  )
  await handler.handle(command)
  name.value = ''
  xp.value = 0
  cr.value = ''
  emit('monster:added')
}
</script>

<template>
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
        <v-text-field
          v-model="xp"
          label="XP"
          density="compact"
          type="number"
        />
      </v-col>
      <v-col
        cols="3"
      >
        <v-text-field
          v-model="cr"
          label="CR"
          density="compact"
          append-icon="mdi-send"
          @click:append="addRow"
        />
      </v-col>
    </v-row>
  </v-form>
</template>
