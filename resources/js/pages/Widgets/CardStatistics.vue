<script setup>
const props = defineProps({
  bookingCount: Number,
  travelersCount: Number,
  totalSales: String,
  totalRevenue: String,
})

const statistics = [
  {
    title: 'Total Booking',
    stats: props.bookingCount,
    icon: 'tabler-chart-pie-2',
    color: 'primary',
  },
  {
    title: 'Travelers',
    stats: props.travelersCount,
    icon: 'tabler-users',
    color: 'info',
  },
  {
    title: 'Total Sales',
    stats: `${props.totalSales}`,
    icon: 'tabler-shopping-cart',
    color: 'error',
  },
  {
    title: 'Revenue',
    stats: `${props.totalRevenue}`,
    icon: 'tabler-currency-dollar',
    color: 'success',
  },
]
</script>

<template>
  <VCard title="Statistics">
    <template #append>
      <span class="text-sm text-disabled">In Last 30 Days</span>
    </template>

    <VCardText>
      <VRow>
        <VCol
          v-for="item in statistics"
          :key="item.title"
          cols="6"
          md="3"
        >
          <div class="d-flex">
            <VAvatar
              :color="item.color"
              variant="tonal"
              size="42"
              class="me-3"
            >
              <VIcon :icon="item.icon" />
            </VAvatar>

            <div class="d-flex flex-column">
              <span class="text-h5 font-weight-medium">{{ item.stats }}</span>
              <span class="text-sm">
                {{ item.title }}
              </span>
            </div>
          </div>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
