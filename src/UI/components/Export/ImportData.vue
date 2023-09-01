<script lang="ts" setup>
</script>

<template>
  <v-card>
    <template #title>
      Upload file
    </template>
    <template #text>
      <v-file-input
        accept=".json"
        label="Select a json file to import data"
      />
    </template>
    <v-card-actions class="justify-end">
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-upload"
      >
        Upload
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
