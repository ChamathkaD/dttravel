<script setup>
import { Head, router } from "@inertiajs/vue3"
import { Confirm, Notify } from "notiflix"

const props = defineProps({
  contact: Object,
})

const handleDelete = () => {
  Confirm.show(
    `Contact Delete?`,
    `Do you want to delete this contact details?`,
    'Delete',
    'No',
    () => {
      router.delete(route("contacts.destroy", props.contact.id), {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Contact Deleted Successfully!")
        },
      })
    })
}
</script>

<template>
  <Head title="Contact Details"/>

  <Breadcrumbs :booking="contact"/>

  <div class="d-flex justify-end mb-2">
    <VBtn
      variant="elevated"
      color="error"
      @click="handleDelete"
    >
      Delete
    </VBtn>
  </div>

  <VRow>
    <VCol
      cols="12"
      md="8"
    >
      <VCard title="Message">
        <VCardText>
          <div class="d-flex justify-space-between mb-2">
            <span class="text-sm text-disabled">{{ formatDate(contact.created_at) }}</span>
          </div>
          {{ contact.message }}
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <VCard>
        <VCardText class="d-flex flex-column gap-y-6">
          <div class="text-body-1 text-high-emphasis font-weight-medium">
            Contact User Details
          </div>

          <div class="d-flex align-center">
            <VAvatar
              size="32"
              color="primary"
              class="v-profile_photo_path-light-bg primary--text"
              variant="tonal"
            >
              <span>{{ avatarText(contact.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
            </VAvatar>
            <div class="d-flex flex-column ms-3">
              <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ contact.full_name }}</span>
            </div>
          </div>

          <div class="d-flex flex-column gap-y-1">
            <div class="d-flex justify-space-between align-center text-body-2">
              <span class="text-body-1 text-high-emphasis font-weight-medium">Contact Info</span>
            </div>
            <span>Email: {{ contact.email }}</span>
            <span>Mobile: {{ contact.phone }}</span>
            <span>Travel Date: {{ contact.travel_date }}</span>
            <span>Duration: {{ contact.duration }}</span>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
