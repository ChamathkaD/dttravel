<script setup>
import { Link, router } from "@inertiajs/vue3"
import { Confirm, Notify } from "notiflix"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  booking: Object,
})

const { hasRole } = usePermission()

const resolvePaymentStatus = status => {
  if (status === 'Paid')
    return {
      text: 'Paid',
      color: 'success',
    }
  if (status === 'Pending')
    return {
      text: 'Pending',
      color: 'warning',
    }
  if (status === 'Unpaid')
    return {
      text: 'Unpaid',
      color: 'error',
    }
  if (status === 'Partial')
    return {
      text: 'Partial',
      color: 'primary',
    }
}

const resolveStatus = status => {
  if (status === 'InProgress')
    return {
      text: 'InProgress',
      color: 'info',
    }
  if (status === 'Completed')
    return {
      text: 'Completed',
      color: 'success',
    }
  if (status === 'Cancel')
    return {
      text: 'Cancel',
      color: 'error',
    }
}

const handleCancelBooking = id => {
  Confirm.show(
    'Cancel Booking?',
    'Would you want to cancel this booking?',
    'Cancel',
    'Close',
    () => {
      router.get(route("bookings.cancel", id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Booking Cancelled!")
        },
      })
    })
}

const handleCompleteBooking = id => {
  Confirm.show(
    'Complete Booking?',
    'Would you want set as complete this booking?',
    'Set Complete',
    'Close',
    () => {
      router.get(route("bookings.complete", id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Booking Completed!")
        },
      })
    })
}

const handleDelete = () => {
  Confirm.show(
    `Booking Delete?`,
    `Do you want to delete #${props.booking.order_no} booking?`,
    'Delete',
    'No',
    () => {
      router.delete(route("bookings.destroy", props.booking.id), {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Booking Deleted Successfully!")
        },
      })
    })
}
</script>

<template>
  <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
    <div>
      <div class="d-flex gap-2 align-center flex-wrap">
        <h4 class="text-h4">
          Booking #{{ booking.order_no }}
        </h4>
        <div class="d-flex gap-x-2">
          <VChip
            variant="tonal"
            :color="resolvePaymentStatus(booking.payment_status)?.color"
            label
          >
            {{ resolvePaymentStatus(booking.payment_status)?.text }}
          </VChip>
          <VChip
            variant="tonal"
            :color="resolveStatus(booking.status)?.color"
            label
          >
            {{ resolveStatus(booking.status)?.text }}
          </VChip>
        </div>
      </div>
      <div>
        <span class="text-body-1">
          {{ booking.created_at_formatted }}
        </span>
      </div>
    </div>

    <div class="d-flex gap-4 align-center flex-wrap" v-if="booking.status !== 'Cancel'">
      <template v-if="booking.status !== 'Completed'">
        <VBtn
          v-if="booking.payment_status !== 'Paid'"
          variant="text"
          color="error"
          :hidden="booking.status === 'Cancel'"
          @click="handleCancelBooking(booking.id)"
        >
          Cancel
        </VBtn>
      </template>

      <Link :href="route('booking.show-print-traveler-details', booking.id)">
        <VBtn
          variant="tonal"
          color="dark"
          prepend-icon="tabler-printer"
        >
          Print Traveler Detail
        </VBtn>
      </Link>

      <Link :href="route('booking.show-invoice', booking.id)">
        <VBtn
          variant="outlined"
          color="dark"
          prepend-icon="tabler-printer"
        >
          Print Invoice
        </VBtn>
      </Link>

      <VBtn
        v-show="!hasRole('Travel Agent')"
        variant="tonal"
        color="primary"
        :hidden="booking.status === 'Completed'"
        @click="handleCompleteBooking(booking.id)"
      >
        Complete Booking
      </VBtn>
    </div>

    <div v-if="booking.status === 'Cancel'" class="d-flex justify-end align-center mb-2">
      <VBtn
        variant="elevated"
        color="error"
        @click="handleDelete"
      >
        Delete
      </VBtn>
    </div>
  </div>
</template>
