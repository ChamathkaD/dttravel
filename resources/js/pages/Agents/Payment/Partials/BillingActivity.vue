<script setup>
const props = defineProps({
  agentPayment: Object,
  booking: Object,
  agent: Object,
  priceData: Array,
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <!-- 👉 Billing Activity -->
      <VCard title="Billing Activity">
        <VCardText>
          <VTimeline
            truncate-line="both"
            align="start"
            side="end"
            line-color="secondary"
            density="compact"
            class="v-timeline-density-compact"
          >
            <VTimelineItem
              dot-color="secondary"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center">
                <div class="app-timeline-title">
                  Billing Date
                </div>
                <div class="app-timeline-meta" />
              </div>
              <p class="app-timeline-text mb-0">
                {{ agentPayment.billing_date }}
              </p>
            </VTimelineItem>

            <VTimelineItem
              v-if="agentPayment.status !== 'paid'"
              dot-color="secondary"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center">
                <span class="app-timeline-title">Payment Processing</span>
              </div>
              <p class="app-timeline-text mb-0">
                {{ agentPayment.b2b_amount.toFixed(2) }}
              </p>
            </VTimelineItem>

            <VTimelineItem
              v-else
              dot-color="secondary"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center">
                <span class="app-timeline-title">{{ agentPayment.status.toUpperCase() }}</span>
                <!--                <span class="app-timeline-meta">{{ agentPayment.updated_at }}</span> -->
              </div>
              <p class="app-timeline-text mb-0">
                {{ agentPayment.b2b_amount.toFixed(2) }}
              </p>
            </VTimelineItem>
          </VTimeline>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
