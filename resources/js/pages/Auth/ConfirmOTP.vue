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
import { Head, Link, useForm } from '@inertiajs/vue3'

const status = ref('')

const form = useForm({
  id: route().params.user,
  otp: '',
})

const submit = () => {
  form.post(route('user.confirm-otp'))
}
</script>

<template>
  <Head title="Request OTP" />

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
            Please sign in to your account and start the adventure
          </p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="submit">
            <VRow>
              <!-- email -->
              <VCol
                cols="8"
                class="mx-auto"
              >
                <VOtpInput
                  v-model="form.otp"
                  type="number"
                  length="4"
                  label="Your Email"
                />

                <div
                  v-show="form.errors.otp"
                  class="text-sm text-error text-center"
                >
                  {{ form.errors.otp }}
                </div>
              </VCol>


              <VCol cols="12">
                <VBtn
                  class="bg-primary-700"
                  :class="{'opacity-50': form.processing}"
                  block
                  type="submit"
                  :disabled="form.processing"
                >
                  Sign in with OTP
                </VBtn>
              </VCol>

              <VCol cols="12">
                <div class="d-flex justify-center align-center flex-wrap">
                  <span class="me-1">Didn't get the code?</span>
                  <a href="#">Resend</a>
                </div>
              </VCol>

              <VCol
                cols="12"
                class="text-center font-weight-medium"
              >
                <a
                  class="text-gray-900 ms-2"
                  :href="route('login')"
                >
                  Back to Login
                </a>
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
