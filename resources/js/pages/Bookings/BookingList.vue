<script setup>
import { Head, Link } from "@inertiajs/vue3"
import logo from "@images/logo.svg"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  bookings: Object,
})

const search = ref('')
const { hasRole } = usePermission()

// Data table options
const itemsPerPage = ref(10)
const page = ref(1)

// Data table Headers
const headers = [
  {
    title: 'Order',
    key: 'order_no',
  },
  {
    title: 'Travel Date',
    key: 'travel_date',
  },
  {
    title: 'Agent',
    key: 'agent',
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
  {
    title: 'Order Date',
    key: 'created_at',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]

const filteredHeaders = computed(() => {
  return headers.filter(header => {
    if (hasRole('Travel Agent')) {
      return header.title !== 'Agent'
    } else {
      return header
    }
  })
})

const resolvePageName = () => {
  if (route().params.status === 'completed') {
    return 'Completed Booking'
  } else if (route().params.status === 'cancel') {
    return 'Cancel Booking'
  } else {
    return 'New Booking'
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

const resolveStatus = status => {
  if (status === 'InProgress')
    return {
      text: 'InProgress',
      color: 'info',
    }
  if (status === 'Completed')
    return {
      text: 'Completed',
      color: 'success',
    }
  if (status === 'Cancel')
    return {
      text: 'Cancel',
      color: 'error',
    }
}
</script>

<template>
  <Head :title="resolvePageName()"/>
  <Breadcrumbs/>
  <VCard>
    <!-- 👉 Filters -->
    <VCardText>
      <div class="d-flex justify-sm-space-between justify-start flex-wrap gap-4">
        <VTextField
          v-model="search"
          density="compact"
          placeholder="Search Order"
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
      :headers="filteredHeaders"
      :search="search"
      :items="bookings"
      :items-per-page="10"
      class="text-no-wrap"
    >
      <!-- Order ID -->
      <template #item.order_no="{ item }">
        <RouterLink
          :to="{ name: 'apps-ecommerce-order-details-id', params: { id: item.order } }"
          class="d-block font-weight-medium text-truncate text-primary"
        >
          #{{ item.order_no }}
        </RouterLink>
      </template>

      <!-- Date -->
      <template #item.date="{ item }">
        {{ new Date(item.date).toDateString() }}
      </template>

      <!-- Agent -->
      <template #item.agent="{ item }">
        <div
          v-if="item.agent_id !== null"
          class="d-flex align-center"
        >
          <template v-if="item.agent">
            <VAvatar
              size="32"
              :color="item.agent?.profile_photo_path ? '' : 'primary'"
              :class="item.agent?.profile_photo_path ? '' : 'v-avatar-light-bg primary--text'"
              :variant="!item.agent?.profile_photo_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="item.agent?.profile_photo_path"
                :src="item.agent?.profile_photo_url"
              />
              <span v-else>{{ avatarText(item.agent?.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
            </VAvatar>
            <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text-truncate text-primary">
              {{ item.agent?.full_name }}
            </span>
              <span class="text-sm text-medium-emphasis">
              ID: {{ item.agent?.id }}
            </span>
            </div>
          </template>
          <template v-else>
            <span class="text-error">Agent Deleted</span>
          </template>
        </div>
        <div
          v-else
          class="d-flex align-center"
        >
          <VAvatar size="32">
            <VImg :src="logo"/>
          </VAvatar>
          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text-truncate text-primary">
              DT Traveler
            </span>
          </div>
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
      <template #item.actions="{ item }">
        <Link :href="route('bookings.show', item.id)">
          <IconBtn>
            <VIcon icon="tabler-eye"/>
          </IconBtn>
        </Link>
      </template>
    </VDataTable>
  </VCard>
</template>
