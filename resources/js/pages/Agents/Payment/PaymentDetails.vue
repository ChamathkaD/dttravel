<script setup>
import { Head } from "@inertiajs/vue3"
import PaymentActionHeader from "@/pages/Agents/Payment/Partials/PaymentActionHeader.vue"
import BookingDetailsTable from "@/pages/Agents/Payment/Partials/BookingDetailsTable.vue"
import BillingActivity from "@/pages/Agents/Payment/Partials/BillingActivity.vue"

const props = defineProps({
  agentPayment: Object,
  booking: Object,
  agent: Object,
  priceData: Array,
})
</script>

<template>
  <Head title="Payment Details" />

  <Breadcrumbs />

  <PaymentActionHeader :agent-payment="agentPayment" />

  <BookingDetailsTable
    :booking="booking"
    :agent="agent"
    :price-data="priceData"
    :agent-payment="agentPayment"
  />

  <BillingActivity :agent-payment="agentPayment" />
</template>
