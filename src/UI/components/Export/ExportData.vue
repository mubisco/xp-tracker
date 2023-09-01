<script lang="ts" setup>
</script>

<template>
  <v-card>
    <template #title>
      Save data file
    </template>
    <template #text>
      <v-text-field
        label="File name"
        type="text"
      />
    </template>
    <v-card-actions class="justify-end">
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-content-save"
      >
        Save
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
