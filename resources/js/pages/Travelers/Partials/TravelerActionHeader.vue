<script setup>
import { Confirm, Notify } from "notiflix"
import { router } from "@inertiajs/vue3"
import TravelerEditDialog from "@/pages/Travelers/Dialog/TravelerEditDialog.vue"
import { usePermission } from "@/composables/usePermission"
import { ref } from "vue"

const props = defineProps({
  traveler: Object,
  countries: Object,
  travelers: Object,
  languages: Object,
})

const { hasRole } = usePermission()

const isEditDialogVisible = ref(false)

const handleDeleteTraveler = () => {
  Confirm.show(
    `Agent Traveler?`,
    `Do you want to delete "${props.traveler.full_name}" traveler?`,
    'Delete',
    'No',
    () => {
      router.delete(route("travelers.destroy", props.traveler.id), {
        onSuccess: () => {
          Notify.success("Traveler Deleted Successfully!")
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
          Traveler ID #{{ traveler.id }}
        </h4>
      </div>
      <div>
        <span class="text-body-1">
          {{ traveler.created_at_formatted }}
        </span>
      </div>
    </div>

    <div class="d-flex gap-4 align-center flex-wrap">
      <VBtn
        v-if="!hasRole('Staff Member')"
        variant="outlined"
        color="error"
        @click="handleDeleteTraveler"
      >
        Delete Traveler
      </VBtn>

      <VBtn
        v-if="!hasRole('Staff Member')"
        color="primary-800"
        @click="isEditDialogVisible = true"
      >
        Edit Details
      </VBtn>
    </div>
  </div>

  <!--  Traveler Edit Dialog -->
  <TravelerEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :traveler-data="traveler"
    :countries="countries"
    :travelers="travelers"
    :languages="languages"
  />
</template>
