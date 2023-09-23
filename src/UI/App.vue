<script lang="ts" setup>
import AppBar from '@/UI/layouts/appbar/AppBar.vue'
import GlobalSnackbar from '@/UI/components/GlobalSnackbar.vue'

</script>
<template>
  <v-app>
    <AppBar />
    <v-main>
      <GlobalSnackbar />
      <router-view />
    </v-main>
    <v-bottom-navigation>
      <v-btn
        :to="{ name: 'Home' }"
        value="players"
      >
        <v-icon>mdi-account-box-multiple</v-icon>
        <span>Players</span>
      </v-btn>
      <v-btn
        :to="{ name: 'Encounter' }"
        value="encounters"
      >
        <v-icon>mdi-sword-cross</v-icon>
        <span>Encounters</span>
      </v-btn>
      <v-btn
        :to="{ name: 'ImportExport' }"
        value="encounters"
      >
        <v-icon>mdi-swap-vertical-bold</v-icon>
        <span>Import/Export</span>
      </v-btn>
    </v-bottom-navigation>
  </v-app>
</template>
