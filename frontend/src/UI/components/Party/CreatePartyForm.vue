<script lang="ts" setup>
import { useSnackbarStore } from '@/UI/store/snackbar'
import { usePartyStore } from '@/UI/store/parties'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const snackbarStore = useSnackbarStore()
const partyStore = usePartyStore()

const rules = ref({
  nameNotEmpty: (value: string) => !!value || 'Name must not be empty'
})
const name = ref('')
const isValid = computed((): boolean => name.value !== '')

const createParty = async () => {
  partyStore.createParty(name.value)
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
