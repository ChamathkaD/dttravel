<script>
import BlankLayout from "@/layouts/BlankLayout.vue"

export default {
  layout: BlankLayout,
}
</script>

<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { Link, Head, useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const isPasswordVisible = ref(false)

const handleLogin = () => {
  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Sign in" />

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
            Please sign-in to your account and start the adventure
          </p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="handleLogin">
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

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="Password"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  :error-messages="form.errors.password"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <!-- forgot password -->
                <div class="d-flex align-center justify-end flex-wrap mt-2 mb-4">
                  <a
                    class="text-primary-600 ms-2 mb-1"
                    :href="route('password.request')"
                  >
                    Forgot Password?
                  </a>
                </div>

                <!-- login button -->
                <VBtn
                  class="bg-primary-700"
                  :class="{'opacity-50': form.processing}"
                  block
                  type="submit"
                  :disabled="form.processing"
                >
                  Sign in
                </VBtn>
              </VCol>

              <VCol
                cols="12"
                class="text-center font-weight-medium"
              >
                <a
                  class="text-gray-900 ms-2"
                  :href="route('user.otp-request')"
                >
                  Sign in with OTP
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
