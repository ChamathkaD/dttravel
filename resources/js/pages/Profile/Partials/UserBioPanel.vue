<script setup>
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})

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

const resolveUserRoleVariant = role => {
  const roleLowerCase = role.toLowerCase()
  if (roleLowerCase === 'super administrator')
    return {
      color: 'error',
      icon: 'tabler-device-laptop',
    }
  if (roleLowerCase === 'staff member')
    return {
      color: 'success',
      icon: 'tabler-user',
    }
  if (roleLowerCase === 'traveler')
    return {
      color: 'primary',
      icon: 'tabler-user-heart',
    }
  if (roleLowerCase === 'travel agent')
    return {
      color: 'secondary',
      icon: 'tabler-user-square-rounded',
    }

  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}
</script>

<template>
  <VRow>
    <!-- SECTION User Details -->
    <VCol cols="12">
      <VCard v-if="props.user">
        <VCardText class="text-center pt-15">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            :size="100"
            :color="!props.user.profile_photo_path ? 'primary' : undefined"
            :variant="!props.user.profile_photo_path ? 'tonal' : undefined"
          >
            <VImg
              v-if="props.user.profile_photo_path"
              :src="props.user.profile_photo_url"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(props.user.full_name.split(' ').slice(0, 2).join(' ')) }}
            </span>
          </VAvatar>

          <!-- 👉 User fullName -->
          <h6 class="text-h4 mt-4">
            {{ props.user.full_name }}
          </h6>

          <!-- 👉 Role chip -->
          <VChip
            label
            :color="resolveUserRoleVariant(props.user.role_name).color"
            size="small"
            class="text-capitalize mt-3"
          >
            {{ props.user.role_name }}
          </VChip>
        </VCardText>

        <VDivider />

        <!-- 👉 Details -->
        <VCardText>
          <p class="text-sm text-uppercase text-disabled">
            Details
          </p>

          <!-- 👉 User Details list -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Full Name:
                  <span class="text-body-1">
                    {{ props.user.full_name }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Email:
                  <span class="text-body-1">{{ props.user.email }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Status:

                  <VChip
                    label
                    size="small"
                    :color="resolveUserStatusVariant('success')"
                    class="text-capitalize"
                  >
                    {{ props.user.status }}
                  </VChip>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Role:
                  <span class="text-capitalize text-body-1">{{ props.user.role_name }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Contact:
                  <span class="text-body-1">{{ props.user.contact }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.75rem;
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
