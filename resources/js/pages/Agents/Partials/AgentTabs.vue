<script setup>
import AgentPaymentsTable from "@/pages/Agents/Partials/AgentPaymentsTable.vue"
import AgentBookingTable from "@/pages/Agents/Partials/AgentBookingTable.vue"

defineProps({
  agentBookings: Object,
  agentPayments: Object,
})

const currentTab = ref('agent-booking-tab')
</script>

<template>
  <VCard class="mt-10 pt-2">
    <VTabs v-model="currentTab">
      <VTab>Agent Booking</VTab>
      <VTab>Payments</VTab>
    </VTabs>
  </VCard>

  <VWindow
    v-model="currentTab"
    class="mt-10"
  >
    <VWindowItem value="agent-booking-tab">
      <AgentBookingTable :agent-bookings="agentBookings"/>
    </VWindowItem>

    <VWindowItem value="agent-payments-tab">
      <AgentPaymentsTable :agent-payments="agentPayments"/>
    </VWindowItem>
  </VWindow>
</template>
