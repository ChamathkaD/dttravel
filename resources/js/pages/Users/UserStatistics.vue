<script setup>
import girlUsingMobile from '@images/pages/girl-using-mobile.png'
import UserCreateModal from "@/pages/Users/UserCreateModal.vue"

const props = defineProps({
  users: Object,
  roleNames: Array,
})

const userStatistics = [
  {
    title: 'Super Administrators',
    color: 'error',
    icon: 'tabler-device-laptop',
    stats: props.users.filter(user => user.role_name === 'Super Administrator').length,
    users: props.users.filter(user => user.role_name === 'Super Administrator'),
  },
  {
    title: 'Staff Members',
    color: 'success',
    icon: 'tabler-user',
    stats: props.users.filter(user => user.role_name === 'Staff Member').length,
    users: props.users.filter(user => user.role_name === 'Staff Member'),
  },
]
</script>

<template>
  <div class="d-flex mb-6">
    <VRow>
      <VCol
        v-for="statistics in userStatistics"
        :key="statistics.title"
        cols="12"
        sm="6"
        md="4"
      >
        <VCard
          class="h-100"
          :ripple="false"
        >
          <VCardText class="d-flex align-center pb-1">
            <span>Total {{ statistics.stats }} users</span>

            <VSpacer />

            <div class="v-avatar-group">
              <template
                v-for="(user, index) in statistics.users"
                :key="user"
              >
                <VAvatar
                  v-if="statistics.users.length > 4 && statistics.users.length !== 4 && index < 3"
                  size="36"
                  color="primary"
                  variant="tonal"
                >
                  <VImg
                    v-if="user.profile_photo_path"
                    :src="user.profile_photo_url"
                  />

                  <span
                    v-else
                    class="text-sm font-weight-medium"
                  >
                    {{ avatarText(user.full_name.split(' ').slice(0, 2).join(' ')) }}
                  </span>
                </VAvatar>

                <VAvatar
                  v-if="statistics.users.length === 4"
                  size="36"
                  :image="user"
                />
              </template>
              <VAvatar
                v-if="statistics.users.length > 4"
                :color="$vuetify.theme.current.dark ? '#4A5072' : '#f6f6f7'"
              >
                <span>
                  +{{ statistics.users.length - 3 }}
                </span>
              </VAvatar>
            </div>
          </VCardText>

          <VCardText class="pb-5">
            <h4 class="text-h4">
              {{ statistics.title }}
            </h4>
          </VCardText>
        </VCard>
      </VCol>

      <VCol
        cols="12"
        sm="6"
        lg="4"
      >
        <VCard
          class="h-100"
          :ripple="false"
        >
          <VRow
            no-gutters
            class="h-100"
          >
            <VCol
              cols="5"
              class="d-flex flex-column justify-end align-center mt-5"
            >
              <img
                width="85"
                :src="girlUsingMobile"
                alt="girlUsingMobile"
              >
            </VCol>

            <VCol cols="7">
              <VCardText class="d-flex flex-column align-end justify-end gap-2 h-100">
                <UserCreateModal :roles="roleNames" />
                <span class="text-end">Add user, if it doesn't exist.</span>
              </VCardText>
            </VCol>
          </VRow>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>
