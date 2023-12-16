<script lang="ts" setup>
import { ImportDataCommand } from '@/Application/Configuration/Command/ImportDataCommand'
import { ImportDataCommandHandlerProvider } from '@/Infrastructure/Configuration/Provider/ImportDataCommandHandlerProvider'
import { useSnackbarStore } from '@/UI/store/snackbar'
import { computed, ref } from 'vue'

const snackbarStore = useSnackbarStore()
const provider = new ImportDataCommandHandlerProvider()
const content = ref('')

const readyToUpload = computed((): boolean => {
  return content.value !== ''
})

const readFiles = (event: Event) => {
  content.value = ''
  const eventTarget = event.target as HTMLInputElement | null
  const files = eventTarget?.files
  const file = files ? files[0] : null
  if (file === null) {
    return
  }
  const reader = new FileReader()
  reader.readAsText(file, 'UTF-8')
  reader.onloadend = (readerEvent: ProgressEvent<FileReader>) => {
    if (readerEvent?.target?.result) {
      content.value = readerEvent.target.result.toString()
    }
  }
}

const importData = async (): Promise<void> => {
  const command = new ImportDataCommand(btoa(content.value))
  const handler = provider.provide()
  await handler.handle(command)
  snackbarStore.addMessage('Data imported successfully!!!', 'success')
}
</script>

<template>
  <v-card>
    <template #title>
      Upload data file
    </template>
    <template #text>
      <v-file-input
        accept=".json"
        label="Select a json file to import data"
        @change="readFiles"
      />
    </template>
    <v-card-actions class="justify-end">
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-upload"
        :disabled="!readyToUpload"
        @click="importData"
      >
        Upload
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
