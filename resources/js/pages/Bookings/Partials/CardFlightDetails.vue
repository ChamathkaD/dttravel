<script setup>
const props = defineProps({
  booking: Object,
})
</script>

<template>
  <VCard
    title="Flight Details"
    class="mt-5"
  >
    <VCardText>
      <VList lines="two">
        <template
          v-for="(bookingTraveler, index) of booking?.booking_travelers"
          :key="index"
        >
          <VListItem>
            <VListItemTitle>
              {{ bookingTraveler.flight_number ?? '-' }}
            </VListItemTitle>
            <VListItemSubtitle class="mt-1">
              <span class="text-disabled">{{ bookingTraveler.flight_date ?? '-' }}</span>
            </VListItemSubtitle>

            <template #append>
              <div
                class="d-flex pe-6"
                style="border-right: 1px dashed lightgray"
              >
                <VIcon
                  icon="tabler-plane-departure"
                  class="me-3"
                />
                <div>
                  <div class="text-body-1 font-weight-medium">
                    {{ bookingTraveler.dispatcher_time ?? '-' }}
                  </div>
                  <span class="text-sm text-disabled">{{ bookingTraveler.dispatcher_airport ?? '-' }}</span>
                </div>
              </div>

              <div class="d-flex ms-5">
                <VIcon
                  icon="tabler-plane-arrival"
                  class="me-3"
                />
                <div>
                  <div class="text-body-1 font-weight-medium">
                    {{ bookingTraveler.arrival_time ?? '-' }}
                  </div>
                  <span class="text-sm text-disabled">{{ bookingTraveler.arrival_airport ?? '-' }}</span>
                </div>
              </div>
            </template>
          </VListItem>
          <VDivider v-if="index !== booking.booking_travelers.length - 1" />
        </template>
      </VList>
    </VCardText>
  </VCard>
</template>
