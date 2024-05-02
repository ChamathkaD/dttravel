<script setup>
import { ref } from "vue"
import AgentEditDialog from "@/pages/Agents/Dialog/AgentEditDialog.vue"
import { Confirm, Notify } from "notiflix"
import { router } from "@inertiajs/vue3"

const props = defineProps({
  agent: Object,
  countries: Object,
})

const isEditDialogVisible = ref(false)

const handleDeleteAgent = () => {
  Confirm.show(
    `Agent Delete?`,
    `Do you want to delete "${props.agent.full_name}" agent?`,
    'Delete',
    'No',
    () => {
      router.delete(route("agents.destroy", props.agent.id), {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Agent Deleted Successfully!")
        },
      })
    })
}
</script>

<template>
  <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
    <div>
      <div class="d-flex gap-2 align-center mb-2 flex-wrap">
        <h4 class="text-h4">
          Agent ID #{{ agent.id }}
        </h4>
      </div>
      <div>
        <span class="text-body-1">
          {{ agent.created_at_formatted }}
        </span>
      </div>
    </div>

    <div class="d-flex gap-4 align-center flex-wrap">
      <VBtn
        variant="outlined"
        color="error"
        @click="handleDeleteAgent"
      >
        Trash Agent
      </VBtn>

      <VBtn
        color="primary-800"
        @click="() => {isEditDialogVisible = true}"
      >
        Edit Details
      </VBtn>
    </div>
  </div>

  <!-- Agent Edit Dialog -->
  <AgentEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :agent-data="agent"
    :countries="countries"
  />
</template>
