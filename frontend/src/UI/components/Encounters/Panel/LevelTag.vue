<script lang="ts" setup>
import { computed } from 'vue'

const props = defineProps({
  level: { type: String, required: true },
  status: { type: String, required: true }
})

const colorTag = computed(() => {
  if (props.status === 'RESOLVED') {
    return 'grey'
  }
  if (props.level === 'EASY') {
    return 'green-darken-1'
  }
  if (props.level === 'MEDIUM') {
    return 'yellow-darken-1'
  }
  if (props.level === 'HARD') {
    return 'orange-darken-1'
  }
  if (props.level === 'DEADLY') {
    return 'red-darken-2'
  }
  return 'blue'
})
</script>
<template>
  <v-icon
    v-if="status === 'RESOLVED'"
    color="success"
    icon="mdi-check-circle"
    size="large"
  />
  <v-chip
    class="mr-2 ml-2"
    variant="flat"
    size="small"
    label
    :color="colorTag"
  >
    {{ level }}
  </v-chip>
</template>
