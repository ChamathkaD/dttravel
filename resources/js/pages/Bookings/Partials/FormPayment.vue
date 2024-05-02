<script setup>
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"

const props = defineProps({
  booking: Object,
})

const form = useForm({
  total_price: props.booking.total_price,
})

const handlePaymentForm = () => {
  form.get(route('bookings.booking-invite-payments', props.booking.id), {
    preserveScroll: true,
    onSuccess: () => {
      Notify.success("Payment Notification Sent Successfully!")
    },
  })
}
</script>

<template>
  <VCard v-if="booking.status !== 'Cancel'" class="mb-6">
    <VCardText>
      <AppTextField
        v-model="form.total_price"
        label="Add Paymanet Amount"
        class="mb-4"
        :error-messages="form.errors.total_price"
      />

      <VBtn
        color="secondary"
        @click="handlePaymentForm"
      >
        Send Payment
      </VBtn>
    </VCardText>
  </VCard>
</template>
