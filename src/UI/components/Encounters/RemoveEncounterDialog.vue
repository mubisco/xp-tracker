<script lang="ts" setup>
import { computed } from 'vue'
import { useSnackbarStore } from '@/UI/store/snackbar'
import { DeleteEncounterCommand } from '@/Application/Encounter/Command/DeleteEncounterCommand'
import { DeleteEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/DeleteEncounterCommandHandlerProvider'

const props = defineProps({
  encounterUlid: { type: String, required: true },
  modelValue: { type: Boolean, required: true }
})

const emit = defineEmits(['update:modelValue'])
const useCaseProvider = new DeleteEncounterCommandHandlerProvider()
const snackbarStore = useSnackbarStore()

const onDeleteConfirmationClicked = async () => {
  const handler = useCaseProvider.provide()
  const command = new DeleteEncounterCommand(props.encounterUlid)
  await handler.handle(command)
  showDialog.value = false
  snackbarStore.addMessage('Encounter deleted successfully!!', 'success')
}

const showDialog = computed({
  get () { return props.modelValue },
  set (value) { emit('update:modelValue', value) }
})

</script>
<template>
  <v-dialog
    v-model="showDialog"
    width="auto"
  >
    <v-card>
      <v-card-text>
        Are you sure you want to delete this encounter?
      </v-card-text>
      <v-card-actions>
        <v-btn
          variant="elevated"
          color="error"
          block
          @click="onDeleteConfirmationClicked()"
        >
          Confirm
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
