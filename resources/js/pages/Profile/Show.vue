<script setup>
import UserBioPanel from "@/pages/Profile/Partials/UserBioPanel.vue"
import UserTabAccount from "@/pages/Profile/Partials/UserTabAccount.vue"
import UserTabSecurity from "@/pages/Profile/Partials/UserTabSecurity.vue"
import { Head } from "@inertiajs/vue3"

const userTab = ref(null)

const tabs = [
  {
    icon: 'tabler-user-check',
    title: 'Account',
  },
  {
    icon: 'tabler-lock',
    title: 'Security',
  },
]
</script>

<template>
  <Head title="Profile" />
  <Breadcrumbs />
  <VRow>
    <VCol
      cols="12"
      md="5"
      lg="4"
    >
      <UserBioPanel :user="$page.props.auth.user" />
    </VCol>
    <VCol
      cols="12"
      md="7"
      lg="8"
    >
      <VTabs
        v-model="userTab"
        class="v-tabs-pill"
      >
        <VTab
          v-for="tab in tabs"
          :key="tab.icon"
        >
          <VIcon
            :size="18"
            :icon="tab.icon"
            class="me-1"
          />
          <span>{{ tab.title }}</span>
        </VTab>
      </VTabs>

      <VWindow
        v-model="userTab"
        class="mt-6 disable-tab-transition"
        :touch="false"
      >
        <VWindowItem>
          <UserTabAccount :user="$page.props.auth.user" />
        </VWindowItem>

        <VWindowItem>
          <UserTabSecurity />
        </VWindowItem>
      </VWindow>
    </VCol>
  </VRow>
</template>
