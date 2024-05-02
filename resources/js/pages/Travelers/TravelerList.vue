<script setup>
import { ref } from 'vue'
import { Head, Link } from "@inertiajs/vue3"
import TravelerCreateDialog from "@/pages/Travelers/Dialog/TravelerCreateDialog.vue"
import TravelerEditDialog from "@/pages/Travelers/Dialog/TravelerEditDialog.vue"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  travelers: Object,
  countries: Object,
  languages: Object,
})

const { hasRole } = usePermission()

const search = ref('')
const isEditDialogVisible = ref(false)
const travelerData = ref({})

const filteredTravelers = computed(() => {
  if (route().params.withAgent) {
    return props.travelers.filter(traveler => traveler.agent_id !== null)
  } else {
    if (hasRole('Travel Agent')) {
      return props.travelers
    }

    return props.travelers.filter(traveler => traveler.agent_id === null)
  }
})

const handleEditDialog = item => {
  travelerData.value = item
  isEditDialogVisible.value = true
}

const headers = ref([
  { title: 'Traveler Name & ID', key: 'full_name' },
  { title: route().params.withAgent ? 'Agent Name' : 'Country', key: route().params.withAgent ? 'agent' : 'country' },
  { title: 'Booking', key: 'traveler_bookings_count' },
  { title: 'Total Spent', key: 'total_spent' },
  { title: 'Actions', key: 'actions', sortable: false },
])

const resolvePageName = () => {
  if (route().params.withAgent) {
    return 'Agent Travelers'
  } else {
    return 'Traveler List'
  }
}
</script>

<template>
  <Head :title="resolvePageName()" />
  <Breadcrumbs />

  <section>
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
          <VCol
            cols="12"
            sm="6"
            class="d-flex gap-3 justify-end"
          >
            <TravelerCreateDialog
              :countries="countries"
              :travelers="travelers"
              :languages="languages"
            />
          </VCol>
        </VRow>
      </VCardText>
      <VDataTable
        :headers="headers"
        :search="search"
        :items="filteredTravelers"
        :items-per-page="10"
        class="text-no-wrap"
      >
        <!-- User -->
        <template #item.full_name="{ item }">
          <div class="d-flex align-center">
            <VAvatar
              size="32"
              :color="item.profile_photo_path ? '' : 'primary'"
              :class="item.profile_photo_path ? '' : 'v-avatar-light-bg primary--text'"
              :variant="!item.profile_photo_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="item.profile_photo_path"
                :src="item.profile_photo_url"
              />
              <span v-else>{{ avatarText(item.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
            </VAvatar>
            <div class="d-flex flex-column ms-3">
              <Link
                :href="route('travelers.show', item.id)"
                class="d-block font-weight-medium text-truncate"
              >
                {{ item.full_name }}
              </Link>
              <span class="text-sm text-medium-emphasis">
                ID: {{ item.id }}
              </span>
            </div>
          </div>
        </template>

        <!-- Agent -->
        <template #item.agent="{ item }">
          <div
            v-if="item.agent_id !== null"
            class="d-flex align-center"
          >
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
          </div>
        </template>

        <template #item.country="{ item }">
          <div class="d-flex gap-x-2">
            <img
              v-if="item.country"
              :src="item.countryFlag"
              height="22"
              width="30"
              :alt="item.countryFlag"
            >
            <span class="text-body-1">{{ item.country ? item.country : 'N/A' }}</span>
          </div>
        </template>

        <template #item.total_spent="{ item }">
          <h6 class="text-h6 pe-4">
            {{ item?.traveler_bookings.length > 0 ? item?.traveler_bookings[0].net_amount : 0 }}
          </h6>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <Link :href="route('travelers.show', item.id)">
            <IconBtn>
              <VIcon icon="tabler-eye" />
            </IconBtn>
          </Link>
          <IconBtn
            v-if="$page.props.auth.user.role_name === 'Staff Member' && !route().params.withAgent"
            @click="handleEditDialog(item)"
          >
            <VIcon icon="tabler-edit" />
          </IconBtn>
          <IconBtn
            v-if="!hasRole('Staff Member')"
            @click="handleEditDialog(item)"
          >
            <VIcon icon="tabler-edit" />
          </IconBtn>
        </template>
      </VDataTable>
    </VCard>
  </section>

  <!--  Traveler Edit Dialog -->
  <TravelerEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :traveler-data="travelerData"
    :countries="countries"
    :travelers="travelers"
    :languages="languages"
  />
</template>
