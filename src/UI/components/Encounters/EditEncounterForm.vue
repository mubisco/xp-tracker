<script lang="ts" setup>
import { FindEncounterByIdQuery } from '@/Application/Encounter/Query/FindEncounterByIdQuery'
import { FindEncounterByIdQueryHandler } from '@/Application/Encounter/Query/FindEncounterByIdQueryHandler'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto';
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const useCase = new FindEncounterByIdQueryHandler(
  new LocalStorageEncounterRepository(
    new LocalStorageEncounterSerializerVisitor()
  )
)

const encounter = ref<EncounterDto | null>(null)
const route = useRoute()
const encounterName = computed((): string => {
  return encounter.value ? encounter.value.name : ''
})

onMounted(async () => {
  const encounterId = route.params.encounterId ?? ''
  const query = new FindEncounterByIdQuery(encounterId as string)
  encounter.value = await useCase.handle(query)
})

const currentMonsters = ref([
  {
    name: 'Shadow Dancer',
    cr: 7,
    xp: 2900
  },
  {
    name: 'Shadow Dancer',
    cr: 7,
    xp: 2900
  }
])

const addRow = () => {
  console.log('Row should be added')
}
</script>

<template>
  <v-card>
    <template #title>
      Edit {{ encounterName }} Ecounter
    </template>
    <template #text>
      <v-table density="compact">
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-right">
              CR
            </th>
            <th class="text-right">
              XP
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(monster, index) in currentMonsters"
            :key="index"
          >
            <td>{{ monster.name }}</td>
            <td class="text-right">
              {{ monster.cr }}
            </td>
            <td class="text-right">
              {{ monster.xp }}
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th
              class="text-right"
              colspan="2"
            >
              Total Monsters
            </th>
            <td
              class="text-right"
            >
              2
            </td>
          </tr>
          <tr>
            <th
              class="text-right"
              colspan="2"
            >
              Total XP
            </th>
            <td
              class="text-right"
            >
              5800
            </td>
          </tr>
          <tr>
            <th
              class="text-right"
              colspan="2"
            >
              Total Points
            </th>
            <td
              class="text-right"
            >
              7200
            </td>
          </tr>
        </tfoot>
      </v-table>
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
    <v-card-actions class="justify-space-between">
      <v-btn
        color="primary"
        prepend-icon="mdi-arrow-left"
        :active="false"
        :to="{ name: 'Encounter' }"
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
