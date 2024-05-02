<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { router, usePage } from "@inertiajs/vue3"

const userData = usePage().props.auth.user

const logout = async () => {
  router.post(route("logout"))
}

const userProfileList = [
  { type: 'divider' },
  {
    type: 'navItem',
    icon: 'tabler-user',
    title: 'Profile',
    onClick: () => router.visit(route('profile.show')),
  },

  // {
  //   type: 'navItem',
  //   icon: 'tabler-settings',
  //   title: 'Settings',
  //   to: {
  //     name: 'pages-account-settings-tab',
  //     params: { tab: 'account' },
  //   },
  // },
  // { type: 'divider' },
  {
    type: 'navItem',
    icon: 'tabler-logout',
    title: 'Logout',
    onClick: logout,
  },
]
</script>

<template>
  <VList>
    <VListItem
      :title="userData.full_name"
      :subtitle="userData.role_name"
      class="cursor-pointer"
      style="color:#000;"
    >
      <template #prepend>
        <VBadge
          v-if="userData"
          dot
          bordered
          location="bottom right"
          offset-x="3"
          offset-y="3"
          color="success"
        >
          <VAvatar
            class="cursor-pointer"
            :color="!(userData && userData.profile_photo_path) ? 'primary' : undefined"
            :variant="!(userData && userData.profile_photo_path) ? 'tonal' : undefined"
          >
            <VImg
              v-if="userData && userData.profile_photo_path"
              :src="userData.profile_photo_url"
            />
            <VIcon
              v-else
              icon="tabler-user"
            />
          </VAvatar>
        </VBadge>
      </template>
      <VMenu
        activator="parent"
        width="250"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                  bordered
                >
                  <VAvatar
                    :color="!(userData && userData.profile_photo_path) ? 'primary' : undefined"
                    :variant="!(userData && userData.profile_photo_path) ? 'tonal' : undefined"
                  >
                    <VImg
                      v-if="userData && userData.profile_photo_path"
                      :src="userData.profile_photo_url"
                    />
                    <VIcon
                      v-else
                      icon="tabler-user"
                    />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-medium">
              {{ userData.full_name }}
            </VListItemTitle>
            <VListItemSubtitle>{{ userData.email }}</VListItemSubtitle>
          </VListItem>

          <PerfectScrollbar :options="{ wheelPropagation: false }">
            <template
              v-for="item in userProfileList"
              :key="item.title"
            >
              <VListItem
                v-if="item.type === 'navItem'"
                @click="item.onClick && item.onClick()"
              >
                <template #prepend>
                  <VIcon
                    class="me-2"
                    :icon="item.icon"
                    size="22"
                  />
                </template>

                <VListItemTitle>{{ item.title }}</VListItemTitle>

                <template
                  v-if="item.badgeProps"
                  #append
                >
                  <VBadge v-bind="item.badgeProps" />
                </template>
              </VListItem>
              <VDivider
                v-else
                class="my-2"
              />
            </template>
          </PerfectScrollbar>
        </VList>
      </VMenu>
    </VListItem>
  </VList>
</template>
