<script setup>
import logo from "@images/logo.svg"

const props = defineProps({
  agent: Object,
  booking: Object,
})
</script>

<template>
  <VCard class="mb-6">
    <VCardText class="d-flex flex-column gap-y-6">
      <div class="text-body-1 text-high-emphasis font-weight-medium">
        Agent Details
      </div>

      <div
        v-if="booking.agent_id !== null"
        class="d-flex align-center"
      >
        <template v-if="agent">
          <VAvatar
            size="32"
            :color="agent.profile_photo_path ? '' : 'primary'"
            :class="agent.profile_photo_path ? '' : 'v-profile_photo_path-light-bg primary--text'"
            :variant="!agent.profile_photo_path ? 'tonal' : undefined"
          >
            <VImg
              v-if="agent.profile_photo_path"
              :src="agent.profile_photo_url"
            />
            <span v-else>{{ avatarText(agent.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
          </VAvatar>
          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ agent.full_name }}</span>
            <span class="text-sm text-medium-emphasis">
            Customer ID: # {{ agent.id }}
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

      <div v-if="agent">
        <VAvatar
          variant="tonal"
          color="success"
          class="me-3"
        >
          <VIcon icon="tabler-shopping-cart"/>
        </VAvatar>
        <span class="text-body-1 font-weight-medium text-high-emphasis">12 Orders</span>
      </div>

      <div
        v-if="agent"
        class="d-flex flex-column gap-y-1"
      >
        <div class="d-flex justify-space-between align-center text-body-2">
          <span class="text-body-1 text-high-emphasis font-weight-medium">Contact Info</span>
          <!--          <VBtn -->
          <!--            variant="text" -->
          <!--            density="compact" -->
          <!--            @click="isUserInfoEditDialogVisible = !isUserInfoEditDialogVisible" -->
          <!--          > -->
          <!--            Edit -->
          <!--          </VBtn> -->
        </div>
        <span>Email: {{ agent.email }}</span>
        <span>Mobile: {{ agent.contact }}</span>
      </div>
    </VCardText>
  </VCard>
</template>
