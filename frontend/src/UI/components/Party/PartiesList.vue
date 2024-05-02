<script lang="ts" setup>
import { ref } from 'vue'

const parties = ref([])

</script>
<template>
  <v-card>
    <template #title>
      Parties
    </template>
    <template #text>
      <v-alert
        class="mb-6"
        v-if="parties.length === 0"
        text="Currently there are no parties available. If you want, you may create one, clicking on the button on the top right corner"
        title="No Parties"
        type="info"
      />
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-right">
              Total Characters
            </th>
            <th class="text-right">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(party, index) in parties"
            :key="index"
          >
            <td>{{ party.name }}</td>
            <td>{{ party.totalCharacters }}</td>
            <td class="text-right">
              <v-btn
                icon="mdi-eye"
                variant="plain"
              />
              <v-btn
                icon="mdi-delete"
                color="red"
                variant="plain"
              />
            </td>
          </tr>
        </tbody>
      </v-table>
    </template>
  </v-card>
</template>

