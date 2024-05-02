<script setup>
const searchQuery = ref('')

// Data table options
const itemsPerPage = ref(10)
const page = ref(1)

// Data table Headers
const headers = [
  {
    title: 'Order',
    key: 'order',
  },
  {
    title: 'Date',
    key: 'date',
  },
  {
    title: 'Payment',
    key: 'payment',
    sortable: false,
  },
  {
    title: 'Status',
    key: 'status',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]

const resolvePaymentStatus = status => {
  if (status === 1)
    return {
      text: 'Paid',
      color: 'success',
    }
  if (status === 2)
    return {
      text: 'Pending',
      color: 'warning',
    }
  if (status === 3)
    return {
      text: 'Cancelled',
      color: 'secondary',
    }
  if (status === 4)
    return {
      text: 'Failed',
      color: 'error',
    }
}

const resolveStatus = status => {
  if (status === 'Delivered')
    return {
      text: 'Delivered',
      color: 'success',
    }
  if (status === 'Out for Delivery')
    return {
      text: 'Out for Delivery',
      color: 'primary',
    }
  if (status === 'Ready to Pickup')
    return {
      text: 'Ready to Pickup',
      color: 'info',
    }
  if (status === 'Dispatched')
    return {
      text: 'Dispatched',
      color: 'warning',
    }
}
</script>

<template>
  <VCard>
    <!-- 👉 Filters -->
    <VCardText>
      <div class="d-flex justify-sm-space-between justify-start flex-wrap gap-4">
        <VTextField
          v-model="searchQuery"
          density="compact"
          placeholder="Serach Order"
          style=" max-inline-size: 200px; min-inline-size: 200px;"
        />

        <div class="d-flex gap-x-4 align-center">
          <VBtn
            variant="tonal"
            color="secondary"
            prepend-icon="tabler-screen-share"
            text="Export"
          />
        </div>
      </div>
    </VCardText>

    <VDivider />

    <!-- 👉 Order Table -->
    <VDataTableServer
      v-model:items-per-page="itemsPerPage"
      v-model:page="page"
      :headers="headers"
      :items="orders"
      :items-length="totalOrder"
      show-select
      class="text-no-wrap"
      @update:options="updateOptions"
    >
      <!-- Order ID -->
      <template #item.order="{ item }">
        <RouterLink
          :to="{ name: 'apps-ecommerce-order-details-id', params: { id: item.order } }"
          class="font-weight-medium"
        >
          #{{ item.order }}
        </RouterLink>
      </template>

      <!-- Date -->
      <template #item.date="{ item }">
        {{ new Date(item.date).toDateString() }}
      </template>

      <!-- Payments -->
      <template #item.payment="{ item }">
        <li
          :class="`text-${resolvePaymentStatus(item.payment)?.color}`"
          class="font-weight-medium"
        >
          {{ resolvePaymentStatus(item.payment)?.text }}
        </li>
      </template>

      <!-- Status -->
      <template #item.status="{ item }">
        <VChip
          v-bind="resolveStatus(item.status)"
          label
        />
      </template>

      <!-- Actions -->
      <template #item.actions="{ item }">
        <IconBtn>
          <VIcon icon="tabler-dots-vertical" />
          <VMenu activator="parent">
            <VList>
              <VListItem
                value="view"
                :to="{ name: 'apps-ecommerce-order-details-id', params: { id: item.order } }"
              >
                View
              </VListItem>
              <VListItem value="delete">
                Delete
              </VListItem>
            </VList>
          </VMenu>
        </IconBtn>
      </template>
    </VDataTableServer>
  </VCard>
</template>
