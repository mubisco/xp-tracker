<script lang="ts" setup>
import { useSnackbarStore } from '@/UI/store/snackbar'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { WithFetchPartyWriteModel } from '@/Infrastructure/Party/Persistence/Fetch/WithFetchPartyWriteModel'
import { CreatePartyCommandHandler } from '@/Application/Party/Command/CreatePartyCommandHandler'
import { CreatePartyCommand } from '@/Application/Party/Command/CreatePartyCommand'

const router = useRouter()
const snackbarStore = useSnackbarStore()
const rules = ref({
  nameNotEmpty: (value: string) => !!value || 'Name must not be empty',
})
const name = ref('')
const isValid = computed((): boolean => name.value !== '')

const createParty = async () => {
  const repository = new WithFetchPartyWriteModel('http://localhost:5000/api')
  const handler = new CreatePartyCommandHandler(repository)
  const command = new CreatePartyCommand(name.value)
  await handler.handle(command)
  snackbarStore.addMessage('Party created successfully', 'success')
  router.replace({ name: 'Home' })
}
</script>

<template>
  <v-card>
    <template #title>
      Create Party
    </template>
    <template #text>
      <v-form>
        <v-row>
          <v-col cols="12">
            <v-text-field
              v-model="name"
              label="Party name"
              type="text"
              :rules="[rules.nameNotEmpty]"
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
        :disabled="!isValid"
        @click="createParty"
      >
        Create
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
