<script setup>
defineProps({
  bookingTraveler: Object,
})
</script>

<template>
  <VCardText>
    <div
      class="mb-1 pa-4 rounded"
      style="background-color: #8692D014"
    >
      <VList
        class="transparent-bg"
        lines="two"
      >
        <VListItem>
          <VListItemTitle class="font-weight-bold">
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
      </VList>
    </div>
  </VCardText>
</template>

<style scoped>
.transparent-bg {
  background-color: transparent;
}
</style>
