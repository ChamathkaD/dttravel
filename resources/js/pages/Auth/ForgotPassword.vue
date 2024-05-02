<script>
import BlankLayout from "@/layouts/BlankLayout.vue"

export default {
  layout: BlankLayout,
}
</script>

<script setup>
import { ref } from 'vue'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { Head, useForm } from '@inertiajs/vue3'

definePage({ meta: { layout: 'blank' } })

const status = ref('')

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'), {
    onSuccess: () => {
      status.value = 'We have emailed your password reset link.'
    },
  })
}
</script>

<template>
  <Head title="Forgot Password" />

  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <div class="position-relative my-sm-16">
      <!-- 👉 Auth Card -->
      <VCard
        flat
        class="auth-card pa-4"
        max-width="448"
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
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
          </p>
        </VCardText>

        <VAlert
          v-if="status"
          density="default"
          color="success"
          variant="tonal"
        >
          {{ status }}
        </VAlert>

        <VCardText>
          <VForm @submit.prevent="submit">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  autofocus
                  label="Your Email"
                  type="email"
                  placeholder="yourname@mail.com"
                  :error-messages="form.errors.email"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  class="bg-primary-700"
                  :class="{'opacity-50': form.processing}"
                  block
                  type="submit"
                  :disabled="form.processing"
                >
                  Email Password Reset Link
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </div>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
