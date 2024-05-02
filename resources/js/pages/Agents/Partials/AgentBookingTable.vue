<script setup>
import { Link } from "@inertiajs/vue3"

const props = defineProps({
  agentBookings: Object,
})

console.log(props.agentBookings)

const searchQuery = ref('')

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
    title: 'Traveler',
    key: 'traveler',
  },
  {
    title: 'Payment',
    key: 'payment_status',
    sortable: false,
  },
  {
    title: 'Status',
    key: 'status',
  },

  // {
  //   title: 'Actions',
  //   key: 'actions',
  //   sortable: false,
  // },
]

const resolveStatus = status => {
  if (status === 'Completed')
    return {
      text: 'Completed',
      color: 'success',
    }
  if (status === 'InProgress')
    return {
      text: 'InProgress',
      color: 'primary',
    }
  if (status === 'Cancel')
    return {
      text: 'Cancel',
      color: 'error',
    }
}

const resolvePaymentStatus = status => {
  if (status === 'Paid')
    return {
      text: 'Paid',
      color: 'success',
    }
  if (status === 'Pending')
    return {
      text: 'Pending',
      color: 'warning',
    }
  if (status === 'Unpaid')
    return {
      text: 'Unpaid',
      color: 'error',
    }
  if (status === 'Partial')
    return {
      text: 'Partial',
      color: 'primary',
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

        <!--        <div class="d-flex gap-x-4 align-center"> -->
        <!--          <VBtn -->
        <!--            variant="tonal" -->
        <!--            color="secondary" -->
        <!--            prepend-icon="tabler-screen-share" -->
        <!--            text="Export" -->
        <!--          /> -->
        <!--        </div> -->
      </div>
    </VCardText>

    <VDivider/>

    <!-- 👉 Order Table -->
    <VDataTable
      :headers="headers"
      :items="agentBookings"
      show-select
      class="text-no-wrap"
    >
      <!-- Order ID -->
      <template #item.order="{ item }">
        <RouterLink
          :to="{ name: 'apps-ecommerce-order-details-id', params: { id: item.order } }"
          class="font-weight-medium"
        >
          #{{ item.order_no }}
        </RouterLink>
      </template>

      <!-- Date -->
      <template #item.date="{ item }">
        {{ new Date(item.created_at).toDateString() }}
      </template>

      <!-- Traveler  -->
      <template #item.traveler="{ item }">
        <div v-if="item.traveler" class="d-flex align-center">
          <VAvatar
            size="32"
            :color="item.traveler?.profile_photo_path ? '' : 'primary'"
            :class="item.traveler?.profile_photo_path ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!item.traveler?.profile_photo_path ? 'tonal' : undefined"
          >
            <VImg
              v-if="item.traveler?.profile_photo_path"
              :src="item.traveler?.profile_photo_url"
            />
            <span v-else>{{ avatarText(item.traveler.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
          </VAvatar>
          <div class="d-flex flex-column ms-3">
            <Link
              :href="route('travelers.show', item.traveler?.id)"
              class="d-block font-weight-medium text-truncate"
            >
              {{ item.traveler.full_name }}
            </Link>
            <span class="text-sm text-medium-emphasis">
              ID: {{ item.traveler?.id }}
            </span>
          </div>
        </div>
        <div v-else class="d-flex align-center">
          N/A
        </div>
      </template>

      <!-- Payments -->
      <template #item.payment_status="{ item }">
        <li
          :class="`text-${resolvePaymentStatus(item.payment_status)?.color}`"
          class="font-weight-medium"
        >
          {{ resolvePaymentStatus(item.payment_status)?.text }}
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
      <!--      <template #item.actions="{ item }"> -->
      <!--        <IconBtn> -->
      <!--          <VIcon icon="tabler-dots-vertical" /> -->
      <!--          <VMenu activator="parent"> -->
      <!--            <VList> -->
      <!--              <VListItem -->
      <!--                value="view" -->
      <!--                :to="{ name: 'apps-ecommerce-order-details-id', params: { id: item.order } }" -->
      <!--              > -->
      <!--                View -->
      <!--              </VListItem> -->
      <!--              <VListItem -->
      <!--                value="delete" -->
      <!--                @click="deleteOrder(item.id)" -->
      <!--              > -->
      <!--                Delete -->
      <!--              </VListItem> -->
      <!--            </VList> -->
      <!--          </VMenu> -->
      <!--        </IconBtn> -->
      <!--      </template> -->
    </VDataTable>
  </VCard>
</template>
