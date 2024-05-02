<script setup>
import { Head } from "@inertiajs/vue3"
import AgentBioPanel from "@/pages/Agents/Partials/AgentBioPanel.vue"
import AgentActionHeader from "@/pages/Agents/Partials/AgentActionHeader.vue"
import AgentOverview from "@/pages/Agents/Partials/AgentOverview.vue"
import AgentTabs from "@/pages/Agents/Partials/AgentTabs.vue"

const props = defineProps({
  agent: Object,
  countries: Object,
  totalAgentBookingCount: Number,
  agentBookings: Object,
  agentPayments: Object,
  totalSales: Number,
  totalIncome: Number,
})
</script>

<template>
  <Head :title="`${agent.full_name}`" />

  <Breadcrumbs :agent="agent" />

  <AgentActionHeader
    :agent="agent"
    :countries="countries"
  />

  <AgentBioPanel :agent="agent" />

  <AgentOverview
    :total-agent-booking-count="totalAgentBookingCount"
    :total-sales="totalSales"
    :total-income="totalIncome"
  />

  <AgentTabs
    :agent-bookings="agentBookings"
    :agent-payments="agentPayments"
  />
</template>
