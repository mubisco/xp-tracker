<script lang="ts" setup>

const addRow = () => {
  console.log('Row should be added')
}
</script>

<template>
  <v-form>
    <v-row>
      <v-col
        cols="6"
      >
        <v-text-field
          label="Name"
          density="compact"
          type="text"
        />
      </v-col>
      <v-col
        cols="3"
      >
        <v-text-field
          label="XP"
          density="compact"
          type="number"
        />
      </v-col>
      <v-col
        cols="3"
      >
        <v-text-field
          label="CR"
          density="compact"
          type="number"
          append-icon="mdi-send"
          @click:append="addRow"
        />
      </v-col>
    </v-row>
  </v-form>
</template>
