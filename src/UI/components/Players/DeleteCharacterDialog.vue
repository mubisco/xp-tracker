<script lang="ts" setup>
import { DeleteCharacterCommand } from '@/Application/Character/Command/DeleteCharacterCommand'
import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { computed, ref } from 'vue'

const props = defineProps({
  characterName: { type: String, required: true },
  characterUlid: { type: String, required: true },
  modelValue: { type: Boolean, required: true }
})

const emit = defineEmits(['update:modelValue'])

const showDeleteConfirmationAlert = ref(false)

const onDeleteConfirmationClicked = async () => {
  const handler = new DeleteCharacterCommandHandler(
    new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
  )
  const command = new DeleteCharacterCommand(props.characterUlid)
  await handler.handle(command)
  showDialog.value = false
  showDeleteConfirmationAlert.value = true
}

const showDialog = computed({
  get () {
    return props.modelValue
  },
  set (value) {
    emit('update:modelValue', value)
  }
})

</script>
<template>
  <v-dialog
    v-model="showDialog"
    width="auto"
  >
    <v-card>
      <v-card-text>
        Are you sure to delete this character: {{ characterName }}?
      </v-card-text>
      <v-card-actions>
        <v-btn
          variant="elevated"
          color="error"
          block
          @click="onDeleteConfirmationClicked()"
        >
          Confirm Deletion
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <v-alert
    v-model="showDeleteConfirmationAlert"
    type="success"
    title="Character deleted!!!"
    :text="`${characterName} character deleted succesfully!!`"
  />
</template>
