<script setup>
import { Head } from "@inertiajs/vue3"
import BookingActionHeader from "@/pages/Bookings/Partials/BookingActionHeader.vue"
import BookingDetailsTable from "@/pages/Bookings/Partials/BookingDetailsTable.vue"
import CardTravelNote from "@/pages/Bookings/Partials/CardTravelNote.vue"
import CardFlightDetails from "@/pages/Bookings/Partials/CardFlightDetails.vue"
import CardTravelerDetails from "@/pages/Bookings/Partials/CardTravelerDetails.vue"
import FormPayment from "@/pages/Bookings/Partials/FormPayment.vue"
import FormBookingNote from "@/pages/Bookings/Partials/FormBookingNote.vue"
import CardAgentDetails from "@/pages/Bookings/Partials/CardAgentDetails.vue"
import BookingDetailsActivity from "@/pages/Bookings/Partials/BookingDetailsActivity.vue"
import { usePermission } from "@/composables/usePermission"
import FormTravelerDetails from "@/pages/Bookings/Partials/FormTravelerDetails.vue"

const props = defineProps({
  booking: Object,
  category: Object,
  travelers: Object,
  destination: Object,
  maleCount: Number,
  feMaleCount: Number,
  agent: Object,
})

const { hasRole } = usePermission()
</script>

<template>
  <Head title="Booking Details"/>

  <Breadcrumbs :booking="booking"/>

  <BookingActionHeader :booking="booking"/>

  <VRow>
    <VCol
      cols="12"
      md="8"
    >
      <BookingDetailsTable :booking="booking"/>

      <CardTravelNote :booking-notes="booking.booking_notes"/>

      <FormTravelerDetails
        v-if="booking.booking_travelers.length < (booking.adult_count + booking.child_count)"
        :travelers="travelers" :booking="booking" :agent="agent"
      />

      <CardFlightDetails v-if="booking.booking_travelers.length > 0" :booking="booking"/>

      <CardTravelerDetails :booking-travelers="booking?.booking_travelers"/>

      <!--      <CardTravelerDetailsDown /> -->
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <template v-if="booking.payment_status !== 'Paid'">
        <FormPayment
          v-show="!hasRole('Travel Agent')"
          v-if="booking.status !== 'Completed'"
          :booking="booking"
        />
      </template>


      <FormBookingNote :booking="booking"/>

      <CardAgentDetails :booking="booking" :agent="agent"/>

      <BookingDetailsActivity
        :booking="booking"
        :category="category"
        :destination="destination"
        :female-count="feMaleCount"
        :male-count="maleCount"
      />
    </VCol>
  </VRow>
</template>
