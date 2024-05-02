<script setup>
import { ref } from 'vue'
import { Head, Link } from "@inertiajs/vue3"
import AgentCreateDialog from "@/pages/Agents/Dialog/AgentCreateDialog.vue"
import AgentInviteDialog from "@/pages/Agents/Dialog/AgentInviteDialog.vue"
import AgentEditDialog from "@/pages/Agents/Dialog/AgentEditDialog.vue"

const props = defineProps({
  agents: Object,
  countries: Object,
})

const search = ref('')
const isEditDialogVisible = ref(false)
const agentData = ref({})

const handleEditDialog = item => {
  agentData.value = item
  isEditDialogVisible.value = true
}

const headers = ref([
  { title: 'ID', key: 'id' },
  { title: 'Agent', key: 'full_name' },
  { title: 'Country', key: 'country' },
  { title: 'Status', key: 'status' },
  { title: 'Booking', key: 'agent_bookings_count' },
  { title: 'Actions', key: 'actions', sortable: false },
])

const resolveUserStatusVariant = status => {
  const statLowerCase = status.toLowerCase()
  if (statLowerCase === 'pending')
    return 'warning'
  if (statLowerCase === 'active')
    return 'success'
  if (statLowerCase === 'inactive')
    return 'secondary'

  return 'primary'
}
</script>

<template>
  <Head title="Agent List"/>
  <Breadcrumbs/>

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
            <AgentInviteDialog/>
            <AgentCreateDialog :countries="countries"/>
          </VCol>
        </VRow>
      </VCardText>
      <VDataTable
        :headers="headers"
        :search="search"
        :items="agents"
        :items-per-page="10"
        class="text-no-wrap"
      >
        <!-- id -->
        <template #item.id="{ item }">
          <Link
            style="color: black; font-weight: bold"
            :href="route('agents.show', item.id)"
          >
            {{ item.id }}
          </Link>
        </template>

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
                :href="route('agents.show', item.id)"
                class="d-block font-weight-medium text-truncate"
              >
                {{ item.full_name }}
              </Link>
              <span class="text-sm text-medium-emphasis">
                {{ item.email }}
              </span>
            </div>
          </div>
        </template>

        <!-- Status -->
        <template #item.status="{ item }">
          <VChip
            :color="resolveUserStatusVariant(item.status)"
            size="small"
            label
            class="text-capitalize"
          >
            {{ item.status }}
          </VChip>
        </template>

        <template
          v-if="agents.length < 11"
          #bottom
        />

        <!-- Actions -->
        <template #item.actions="{ item }">
          <Link :href="route('agents.show', item.id)">
            <IconBtn>
              <VIcon icon="tabler-eye"/>
            </IconBtn>
          </Link>
          <IconBtn @click="handleEditDialog(item)">
            <VIcon icon="tabler-edit"/>
          </IconBtn>
        </template>
      </VDataTable>
    </VCard>
  </section>

  <!-- Agent Edit Dialog -->
  <AgentEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :agent-data="agentData"
    :countries="countries"
  />
</template>
