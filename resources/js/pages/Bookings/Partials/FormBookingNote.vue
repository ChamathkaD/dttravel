<script setup>
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"

const props = defineProps({
  booking: Object,
})

const form = useForm({
  booking_id: props.booking.id,
  title: null,
  note: null,
})

const handleCreateBookingNote = () => {
  form.post(route("bookings.create-note"), {
    preserveScroll: true,
    onFinish: () => form.reset(),
    onSuccess: () => {
      Notify.success("Booking Note Created!")
      form.reset()
    },
  })
}
</script>

<template>
  <VCard class="mb-6">
    <VCardText>
      <AppTextField
        v-model="form.title"
        label="Add Booking Note Title"
        class="mb-4"
      />

      <AppTextarea
        v-model="form.note"
        label="Add Booking Note"
        placeholder="Type Here"
        class="mb-4"
        rows="3"
      />

      <VBtn
        color="dark"
        variant="tonal"
        @click.prevent="handleCreateBookingNote"
      >
        Add Booking Note
      </VBtn>
    </VCardText>
  </VCard>
</template>
