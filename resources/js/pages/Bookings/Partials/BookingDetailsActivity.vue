<script setup>
const props = defineProps({
  booking: Object,
  maleCount: Number,
  feMaleCount: Number,
  category: Object,
  destination: Object,
})
</script>

<template>
  <VCard title="Booking Activity">
    <VCardText>
      <VTimeline
        truncate-line="both"
        align="start"
        side="end"
        line-color="primary"
        density="compact"
        class="v-timeline-density-compact"
      >
        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <div class="d-flex justify-space-between align-center">
            <div class="app-timeline-title">
              Travel Date
            </div>
            <!--            <div class="app-timeline-meta"> -->
            <!--              Tuesday 11:29 AM -->
            <!--            </div> -->
          </div>
          <p class="app-timeline-text mb-0">
            {{ booking.travel_date }}
          </p>
        </VTimelineItem>

        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <div class="d-flex justify-space-between align-center">
            <span class="app-timeline-title">Pax</span>
            <!--            <span class="app-timeline-meta">Tuesday 11:29 AM</span> -->
          </div>
          <p class="app-timeline-text mb-0">
            {{ booking.adult_count + booking.child_count }}
          </p>
        </VTimelineItem>

        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <div class="d-flex justify-space-between align-center">
            <span class="app-timeline-title">Pax Category</span>
            <!--            <span class="app-timeline-meta">Tuesday 11:29 AM</span> -->
          </div>
          <p class="app-timeline-text mb-0">
            Adult {{ booking.adult_count }}, Child {{ booking.child_count }}
          </p>
        </VTimelineItem>

        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <div class="d-flex justify-space-between align-center">
            <span class="app-timeline-title">Gender</span>
            <!--            <span class="app-timeline-meta">Tuesday 11:29 AM</span> -->
          </div>
          <p class="app-timeline-text mb-0">
            Male-{{ maleCount ?? 0 }}, Female-{{ feMaleCount ?? 0 }}
          </p>
        </VTimelineItem>

        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <div class="d-flex justify-space-between align-center">
            <span class="app-timeline-title">Package Category</span>
            <!--            <span class="app-timeline-meta">Tuesday 11:29 AM</span> -->
          </div>
          <p class="app-timeline-text mb-0">
            {{ category.name }}
          </p>
        </VTimelineItem>

        <VTimelineItem
          dot-color="primary"
          size="x-small"
        >
          <div class="d-flex justify-space-between align-center">
            <span class="app-timeline-title">Destination</span>
            <!--            <span class="app-timeline-meta">Tuesday 11:29 AM</span> -->
          </div>
          <p class="app-timeline-text mb-0">
            {{ destination.name }}
          </p>
        </VTimelineItem>
      </VTimeline>
    </VCardText>
  </VCard>
</template>
