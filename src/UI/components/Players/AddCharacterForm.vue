<script lang="ts" setup>
</script>

<template>
  <v-card>
    <template #title>
      Add Character
    </template>
    <template #text>
      <v-form>
        <v-row>
          <v-col cols="12">
            <v-text-field
              label="Name"
              type="text"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="6">
            <v-text-field
              label="Experience Points"
              type="number"
            />
          </v-col>
          <v-col cols="3">
            <v-text-field
              label="Level"
              type="number"
            />
          </v-col>
          <v-col cols="3">
            <v-text-field
              label="Hit Points"
              type="number"
            />
          </v-col>
        </v-row>
      </v-form>
    </template>
    <v-card-actions class="justify-space-between">
      <v-btn
        color="primary"
        prepend-icon="mdi-arrow-left"
        :active="false"
        :to="{ name: 'Home' }"
      >
        Cancel
      </v-btn>
      <v-btn
        variant="elevated"
        color="primary"
        prepend-icon="mdi-plus"
      >
        Add
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
