<script>
import BlankLayout from "@/layouts/BlankLayout.vue"

export default {
  layout: BlankLayout,
}
</script>

<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { Link, Head, useForm, router } from '@inertiajs/vue3'
import { ref } from "vue"

const props = defineProps({
  email: String,
  token: String,
})

const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)

const form = useForm({
  token: route().params.token,
  email: route().params.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
    onSuccess: () => window.location.href = route("login"),
  })
}
</script>

<template>
  <Head title="Reset Password" />
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
            Reset Your Password
          </h4>
        </VCardText>

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
                  disabled
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  id="password"
                  v-model="form.password"
                  label="Password"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  :error-messages="form.errors.password"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  label="Confirm Password"
                  placeholder="············"
                  :error-messages="form.errors.password_confirmation"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
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
                  Reset Password
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
