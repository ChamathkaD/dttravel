<script setup>
import { Head } from "@inertiajs/vue3"
import PaymentOverview from "@/pages/Agents/Payment/Partials/PaymentOverview.vue"
import PaymentsTable from "@/pages/Agents/Payment/Partials/PaymentsTable.vue"

const props = defineProps({
  totalBookingCount: Number,
  totalSales: Number,
  totalIncome: Number,
  agentPayments: Object,
})
</script>

<template>
  <Head title="Payment History" />

  <Breadcrumbs />

  <PaymentOverview
    :total-booking-count="totalBookingCount"
    :total-sales="totalSales"
    :total-income="totalIncome"
  />

  <PaymentsTable :agent-payments="agentPayments" />
</template>
