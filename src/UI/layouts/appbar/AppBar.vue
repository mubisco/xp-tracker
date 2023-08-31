<script lang="ts" setup>
import { useRoute } from 'vue-router'

const route = useRoute()

</script>
<template>
  <v-app-bar
    color="primary"
    title="XP Tracker"
  >
    <template #append>
      <v-btn
        v-if="route.name === 'Home'"
        icon="mdi-account-plus"
        :to="{ name: 'AddCharacter' }"
      />
      <v-btn
        v-if="route.name === 'Encounter'"
        icon="mdi-plus"
        :to="{ name: 'AddEncounter' }"
      />
    </template>
  </v-app-bar>
</template>
