<script setup lang="ts">
import { ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useSnackbarStore } from '@/UI/store/snackbar'

const showSnackbar = ref(false)
const snackbarType = ref('success')
const snackbarMessage = ref('')
const snackbarStore = useSnackbarStore()
const { messageCount } = storeToRefs(snackbarStore)

watch(messageCount, () => {
  const message = snackbarStore.consume()
  if (message !== undefined) {
    snackbarType.value = message.messageType
    snackbarMessage.value = message.messageContent
    showSnackbar.value = true
  }
})

</script>
<template>
  <v-snackbar
    v-model="showSnackbar"
    timeout="3000"
    :color="snackbarType"
    location="top right"
  >
    <span>
      {{ snackbarMessage }}
    </span>
    <template #actions>
      <v-btn
        variant="text"
        icon="mdi-close"
        @click="showSnackbar = false"
      />
    </template>
  </v-snackbar>
</template>
