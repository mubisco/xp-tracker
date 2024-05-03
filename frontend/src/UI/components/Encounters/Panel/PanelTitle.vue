<script lang="ts" setup>
import LevelTag from '@/UI/components/Encounters/LevelTag.vue'

defineProps({
  name: { type: String, required: true },
  level: { type: String, required: true },
  status: { type: String, required: true }
})
</script>
<template>
  <v-expansion-panel-title>
    <div class="d-flex w-100">
      <div class="flex-grow-1">
        {{ name }}
      </div>
      <LevelTag
        :level="level"
        :status="status"
      />
    </div>
  </v-expansion-panel-title>
</template>
