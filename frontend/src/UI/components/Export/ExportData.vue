<script lang="ts" setup>
import { ExportDataDto } from '@/Application/Configuration/Query/ExportDataDto'
import { ExportDataQuery } from '@/Application/Configuration/Query/ExportDataQuery'
import { ExportDataQueryHandlerProvider } from '@/Infrastructure/Configuration/Provider/ExportDataQueryHandlerProvider'
import { useSnackbarStore } from '@/UI/store/snackbar'
import { ref } from 'vue'

const filename = ref('')
const provider = new ExportDataQueryHandlerProvider()
const snackbarStore = useSnackbarStore()

const downloadItem = async () => {
  const data = await exportData()
  const content = atob(data.base64content)
  const blob = new Blob([content], { type: 'application/json' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.download = data.filename
  link.click()
  URL.revokeObjectURL(link.href)
  snackbarStore.addMessage(`File ${filename.value} downloaded successfully!!!`, 'success')
  filename.value = ''
}

const exportData = async (): Promise<ExportDataDto> => {
  const query = new ExportDataQuery(filename.value)
  const handler = provider.provide()
  return await handler.handle(query)
}
</script>

<template>
  <v-card>
    <template #title>
      Save data file
    </template>
    <template #text>
      <v-text-field
        v-model="filename"
        label="File name"
        type="text"
      />
    </template>
    <v-card-actions class="justify-end">
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-content-save"
        @click.prevent="downloadItem()"
      >
        Save
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
