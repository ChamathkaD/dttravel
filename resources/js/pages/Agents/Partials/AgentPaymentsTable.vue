<script setup>
import { ref } from "vue"
import { Link } from "@inertiajs/vue3"

const props = defineProps({
  agentPayments: Object,
})

const searchQuery = ref('')
const selectedStatus = ref()

const filteredAgentPayments = computed(() => {
  return selectedStatus.value ? props.agentPayments.filter(payment => payment.status === selectedStatus.value.toLowerCase()) : props.agentPayments
})

const headers = [
  {
    title: '#ID',
    key: 'id',
  },
  {
    title: 'Total Sales',
    key: 'total_sale',
  },
  {
    title: 'Billing Date',
    key: 'billing_date',
  },
  {
    title: 'B2B Amount',
    key: 'b2b_amount',
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

const resolveStatusVariant = status => {
  if (status === 'unpaid')
    return {
      color: 'error',
    }

  if (status === 'paid')
    return {
      color: 'success',
    }
}
</script>

<template>
  <section>
    <VCard id="invoice-list">
      <VCardText class="d-flex">
        <!-- 👉 Search  -->
        <!--        <div class="invoice-list-filter"> -->
        <!--          <AppTextField -->
        <!--            v-model="searchQuery" -->
        <!--            placeholder="Search ID" -->
        <!--            density="compact" -->
        <!--          /> -->
        <!--        </div> -->

        <VSpacer />

        <div class="d-flex">
          <!-- 👉 Select status -->
          <div class="invoice-list-filter">
            <AppSelect
              v-model="selectedStatus"
              placeholder="Select Status"
              clearable
              clear-icon="tabler-x"
              single-line
              :items="['Paid', 'Unpaid']"
              name="select"
            />
          </div>
        </div>
      </VCardText>
      <VDivider />

      <!-- SECTION Datatable -->
      <VDataTable
        :headers="headers"
        :items="filteredAgentPayments"
        class="text-no-wrap"
      >
        <!-- id -->
        <template #item.id="{ item }">
          <RouterLink :to="{ name: 'apps-invoice-preview-id', params: { id: item.id } }">
            #{{ item.id }}
          </RouterLink>
        </template>

        <!-- Total Sales -->
        <template #item.total_sale="{ item }">
          {{ item.total_sale.toFixed(2) }}
        </template>

        <!-- Billing Date -->
        <template #item.billing_date="{ item }">
          {{ item.billing_date }}
        </template>

        <!-- B2B Amount -->
        <template #item.b2b_amount="{ item }">
          {{ item.b2b_amount.toFixed(2) }}
        </template>

        <!-- Status -->
        <template #item.status="{ item }">
          <VChip
            :color="resolveStatusVariant(item.status).color"
            label
          >
            {{ item.status.toUpperCase() }}
          </VChip>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <Link :href="route('agents.payment.details', item.id)">
            <IconBtn>
              <VIcon icon="tabler-eye" />
            </IconBtn>
          </Link>
        </template>
      </VDataTable>
      <!-- !SECTION -->
    </VCard>
  </section>
</template>

<style lang="scss">
#invoice-list {
  .invoice-list-actions {
    inline-size: 8rem;
  }

  .invoice-list-filter {
    inline-size: 12rem;
  }
}
</style>
