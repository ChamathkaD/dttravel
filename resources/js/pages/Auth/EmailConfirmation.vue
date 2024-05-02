<script>
import BlankLayout from "@/layouts/BlankLayout.vue"
import { themeConfig } from "@themeConfig"

export default {
  layout: BlankLayout,
}
</script>

<script setup>
import { Head, Link } from "@inertiajs/vue3"
import { VNodeRenderer } from "@layouts/components/VNodeRenderer"

defineProps({
  user: Object,
  token: String,
})

definePage({ meta: { layout: 'blank' } })
</script>

<template>
  <Head title="Create Account" />

  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <div class="position-relative my-sm-16">
      <!-- 👉 Auth Card -->
      <VCard
        flat
        class="auth-card pa-4"
        width="448"
      >
        <VCardItem class="justify-center">
          <template #prepend>
            <div class="d-flex">
              <VNodeRenderer :nodes="themeConfig.app.logo" />
            </div>
          </template>
        </VCardItem>

        <VCardText class="pt-1 text-center">
          <h4 class="text-h4 font-weight-bold mb-1">
            Welcome to <span class="text-capitalize">{{ themeConfig.app.title }}</span>!
          </h4>
          <p class="mb-0">
            Please sign up to your account
          </p>
        </VCardText>

        <VCardText>
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <AppTextField
                type="email"
                :value="user.email"
                disabled
              />
            </VCol>

            <VCol cols="12">
              <a :href="route('user-account.wizard.create', {'token': token})">
                <VBtn
                  class="bg-primary-700"
                  block
                >
                  Create my account
                </VBtn>
              </a>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </div>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
