<script setup>
defineProps({
  totalAgentBookingCount: Number,
  totalSales: Number,
  totalIncome: Number,
})
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="4"
    >
      <VCard>
        <VCardText class="d-flex gap-y-2 flex-column">
          <VAvatar
            variant="tonal"
            color="primary"
            icon="tabler-backpack"
            rounded
          />
          <h4 class="text-h4">
            Total Booking
          </h4>
          <div>
            <span class="text-primary text-h4 me-2">{{ totalAgentBookingCount }}</span>
          </div>
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <VCard>
        <VCardText class="d-flex gap-y-2 flex-column">
          <VAvatar
            variant="tonal"
            color="success"
            icon="tabler-currency-dollar"
            rounded
          />
          <h4 class="text-h4">
            Total Sales
          </h4>
          <div>
            <span class="text-primary text-h4 me-2">{{ totalSales }}</span>
          </div>
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <VCard>
        <VCardText class="d-flex gap-y-2 flex-column">
          <VAvatar
            variant="tonal"
            color="warning"
            icon="tabler-currency-dollar"
            rounded
          />
          <h4 class="text-h4">
            Total Income
          </h4>
          <div>
            <span class="text-primary text-h4 me-2">{{ totalIncome }}</span>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
