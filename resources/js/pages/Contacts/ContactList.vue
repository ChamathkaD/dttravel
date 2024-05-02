<script setup>
import { ref } from 'vue'
import { Head, Link } from "@inertiajs/vue3"

const props = defineProps({
  contacts: Object,
})

const search = ref('')

const headers = [
  { title: 'User', key: 'full_name' },
  { title: 'Phone', key: 'phone', sortable: false },
  { title: 'Travel Date', key: 'travel_date' },
  { title: 'Duration', key: 'duration', sortable: false },
  { title: 'Message', key: 'message', sortable: false },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]
</script>

<template>
  <Head title="Contact List" />
  <Breadcrumbs />

  <VCard>
    <VCardText>
      <VRow class="d-flex justify-space-between">
        <VCol
          cols="12"
          md="3"
          sm="6"
        >
          <AppTextField
            v-model="search"
            density="compact"
            placeholder="Search ..."
            append-inner-icon="tabler-search"
            single-line
            hide-details
            dense
            outlined
          />
        </VCol>
      </VRow>
    </VCardText>
    <VDataTable
      :headers="headers"
      :search="search"
      :items="contacts"
      :items-per-page="10"
      class="text-no-wrap"
    >
      <!-- User -->
      <template #item.full_name="{ item }">
        <div class="d-flex">
          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{
              item.first_name
            }} {{ item.last_name }}</span>
            <span>{{ item.email }}</span>
          </div>
        </div>
      </template>

      <template #item.message="{ item }">
        <div style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
          {{ item.message }}
        </div>
      </template>

      <!-- Actions -->
      <template #item.actions="{ item }">
        <Link :href="route('contacts.show', item.id)">
          <IconBtn>
            <VIcon icon="tabler-eye" />
          </IconBtn>
        </Link>
      </template>
    </VDataTable>
  </VCard>
</template>
