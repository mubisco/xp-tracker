<script lang="ts" setup>
import { DeleteCharacterCommand } from '@/Application/Character/Command/DeleteCharacterCommand'
import { DeleteCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/DeleteCharacterCommandHandlerProvider'
import { computed } from 'vue'
import { useSnackbarStore } from '@/UI/store/snackbar'

const props = defineProps({
  characterName: { type: String, required: true },
  characterUlid: { type: String, required: true },
  modelValue: { type: Boolean, required: true }
})

const emit = defineEmits(['update:modelValue'])
const useCaseProvider = new DeleteCharacterCommandHandlerProvider()
const snackbarStore = useSnackbarStore()

const onDeleteConfirmationClicked = async () => {
  const handler = useCaseProvider.provide()
  const command = new DeleteCharacterCommand(props.characterUlid)
  await handler.handle(command)
  showDialog.value = false
  snackbarStore.addMessage(`${props.characterName} deleted successfully!!`, 'success')
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
        Are you sure to delete this character: {{ characterName }}?
      </v-card-text>
      <v-card-actions>
        <v-btn
          variant="elevated"
          color="error"
          block
          @click="onDeleteConfirmationClicked()"
        >
          Confirm Deletion
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
