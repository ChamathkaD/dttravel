<script>
import BlankLayout from "@/layouts/BlankLayout.vue"

export default {
  layout: BlankLayout,
}
</script>

<script setup>
import { ref } from 'vue'
import { Head, useForm } from "@inertiajs/vue3"
import { themeConfig } from "@themeConfig"
import { VNodeRenderer } from "@layouts/components/VNodeRenderer"
import { countryCodes } from "@core/countryCodes"

const props = defineProps({
  user: Object,
  countries: Object,
})

definePage({ meta: { layout: 'blank' } })

const currentStep = ref(0)
const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const photoPreview = ref(null)
const photoInput = ref(null)
const logoPreview = ref(null)
const logoInput = ref(null)

const contactInput = ref(null)
const contactCodeInput = ref(null)
const businessContactInput = ref(null)
const businessContactCodeInput = ref(null)

const form = useForm({
  _method: 'PUT',
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email,
  contact: null,
  photo: null,
  business_name: null,
  business_reg_no: null,
  business_contact: null,
  business_email: null,
  business_address: null,
  business_country: null,
  business_city: null,
  logo: null,
  password: null,
  password_confirmation: null,
})

const items = ref([
  {
    title: 'Account',
    subtitle: 'Account Details',
    icon: 'tabler-smart-home',
  },
  {
    title: 'Business',
    subtitle: 'Business Details',
    icon: 'tabler-briefcase',
  },
  {
    title: 'Login',
    subtitle: 'Login Details',
    icon: 'tabler-lock',
  },
])

const filteredItems = computed(() => {
  if (props.user.role_name !== 'Travel Agent') {
    return items.value.filter(item => item.title !== 'Business')
  }

  return items.value
})

const handleUserAccountUpdate = () => {
  form.post(route('user-account.wizard.update', props.user.id), {
    onSuccess: () => {
      form.reset()
    },
  })
}

const nextStep = () => {
  if (currentStep.value === 0) {

    if (photoInput.value) {
      form.photo = photoInput.value.files[0]
    }

    if (contactInput.value && contactCodeInput.value) {
      form.contact = contactCodeInput.value + ' ' + contactInput.value
    }

    form.post(route('user-account.wizard.step-one'), {
      onSuccess: () => {
        currentStep.value++
      },
    })
  }

  if (currentStep.value === 1) {

    if (logoInput.value) {
      form.logo = logoInput.value.files[0]
    }

    if (businessContactInput.value && businessContactCodeInput.value) {
      form.business_contact = businessContactCodeInput.value + ' ' + businessContactInput.value
    }

    form.post(route('user-account.wizard.step-two'), {
      onSuccess: () => {
        console.log('hello')
        currentStep.value++
      },
    })
  }
}

const prevStep = () => {
  currentStep.value--
}

const selectNewPhoto = () => {
  photoInput.value.click()
}

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0]

  if (!photo) return

  const reader = new FileReader()

  reader.onload = e => {
    photoPreview.value = e.target.result
  }

  reader.readAsDataURL(photo)
}

const selectNewLogo = () => {
  logoInput.value.click()
}

const updateLogoPreview = () => {
  const logo = logoInput.value.files[0]

  if (!logo) return

  const reader = new FileReader()

  reader.onload = e => {
    logoPreview.value = e.target.result
  }

  reader.readAsDataURL(logo)
}
</script>

<template>
  <Head title="Create Account"/>
  <VRow
    no-gutters
    class="auth-wrapper"
  >
    <VCol
      cols="12"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
        flat
        class="mt-12 mt-sm-0 pa-10"
      >
        <VCardItem class="justify-center">
          <template #prepend>
            <div class="d-flex">
              <VNodeRenderer :nodes="themeConfig.app.logo"/>
            </div>
          </template>
        </VCardItem>

        <AppStepper
          v-model:current-step="currentStep"
          :items="filteredItems"
          :direction="$vuetify.display.smAndUp ? 'horizontal' : 'vertical'"
          icon-size="24"
          class="stepper-icon-step-bg mb-8"
          :is-active-step-valid="false"
        />

        <VWindow
          v-model="currentStep"
          class="disable-tab-transition"
          style="max-inline-size: 681px;"
        >
          <VWindowItem>
            <h5 class="text-h5 mb-1">
              Account Information
            </h5>
            <p class="text-sm">
              Enter Your Account Details
            </p>

            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.first_name"
                  disabled
                  label="First Name"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.last_name"
                  disabled
                  label="Last Name"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.email"
                  label="Email"
                  disabled
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VRow
                  align="center"
                  no-gutters=""
                >
                  <VCol cols="4">
                    <AppSelect
                      v-model="contactCodeInput"
                      label="Contact Number"
                      placeholder="Code"
                      :error="form.errors.contact"
                      :items="countryCodes"
                    />
                  </VCol>
                  <VCol>
                    <AppTextField
                      v-model="contactInput"
                      label="&nbsp;"
                      :error="form.errors.contact"
                      placeholder="712565898"
                    />
                  </VCol>
                </VRow>
                <VRow no-gutters="">
                  <VCol>
                    <p
                      v-show="form.errors.contact"
                      class="text-sm text-error mt-1"
                    >
                      {{ form.errors.contact }}
                    </p>
                  </VCol>
                </VRow>
              </VCol>

              <VCol cols="12">
                <div class="d-flex gap-6">
                  <!-- New Profile Photo Preview -->
                  <VAvatar
                    v-show="photoPreview"
                    rounded
                    size="100"
                    class="me-6"
                    :image="photoPreview"
                  />

                  <div
                    v-show="!photoPreview"
                    class="border border-dashed rounded-sm pa-6"
                  >
                    <VIcon
                      icon="tabler-camera"
                      size="40"
                    />
                  </div>

                  <!-- 👉 Upload Photo -->
                  <div class="d-flex flex-column justify-center gap-4">
                    <div class="d-flex flex-wrap gap-2">
                      <VBtn
                        variant="outlined"
                        density="comfortable"
                        @click.prevent="selectNewPhoto"
                      >
                        <VIcon
                          icon="tabler-cloud-upload"
                          class="d-sm-none"
                        />
                        <span class="d-none d-sm-block">Upload new photo</span>
                      </VBtn>

                      <input
                        id="photo"
                        ref="photoInput"
                        type="file"
                        hidden
                        @change="updatePhotoPreview"
                      >
                    </div>
                  </div>
                </div>
                <p
                  v-show="form.errors.photo"
                  class="text-sm text-error mt-1"
                >
                  {{ form.errors.photo }}
                </p>
              </VCol>
            </VRow>
          </VWindowItem>

          <VWindowItem v-if="user.role_name === 'Travel Agent'">
            <h5 class="text-h5 mb-1">
              Business Information
            </h5>
            <p class="text-sm">
              Enter Your business information.
            </p>

            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.business_name"
                  :error-messages="form.errors.business_name"
                  label="Business Name"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.business_reg_no"
                  :error-messages="form.errors.business_reg_no"
                  label="Business Registration Number"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VRow
                  align="center"
                  no-gutters=""
                >
                  <VCol cols="4">
                    <AppSelect
                      v-model="businessContactCodeInput"
                      label="Company Contact Number"
                      placeholder="Code"
                      :error="form.errors.business_contact"
                      :items="countryCodes"
                    />
                  </VCol>
                  <VCol>
                    <AppTextField
                      v-model="businessContactInput"
                      :error="form.errors.business_contact"
                      label="&nbsp;"
                      placeholder="712565898"
                    />
                  </VCol>
                </VRow>
                <VRow no-gutters="">
                  <VCol>
                    <p
                      v-show="form.errors.business_contact"
                      class="text-sm text-error mt-1"
                    >
                      {{ form.errors.business_contact }}
                    </p>
                  </VCol>
                </VRow>
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.business_email"
                  :error-messages="form.errors.business_email"
                  type="email"
                  label="Company Contact Email"
                  placeholder="businessname@mail.com"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.business_address"
                  :error-messages="form.errors.business_address"
                  label="Business Address"
                  placeholder="1234 Main St, New York, NY 10001, USA"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppSelect
                  v-model="form.business_country"
                  :error-messages="form.errors.business_country"
                  :items="countries"
                  item-title="name"
                  item-value="name"
                  placeholder="Select Country"
                  label="Business Country"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.business_city"
                  :error-messages="form.errors.business_city"
                  label="City"
                  placeholder="New York"
                />
              </VCol>

              <VCol cols="12">
                <div class="d-flex gap-6">
                  <!-- New Profile Photo Preview -->
                  <VAvatar
                    v-show="logoPreview"
                    rounded
                    size="100"
                    class="me-6"
                    :image="logoPreview"
                  />

                  <div
                    v-show="!logoPreview"
                    class="border border-dashed rounded-sm pa-6"
                  >
                    <VIcon
                      icon="tabler-photo"
                      size="40"
                    />
                  </div>

                  <!-- 👉 Upload Photo -->
                  <div class="d-flex flex-column justify-center gap-4">
                    <div class="d-flex flex-wrap gap-2">
                      <VBtn
                        variant="outlined"
                        density="comfortable"
                        @click.prevent="selectNewLogo"
                      >
                        <VIcon
                          icon="tabler-cloud-upload"
                          class="d-sm-none"
                        />
                        <span class="d-none d-sm-block">Upload Logo</span>
                      </VBtn>

                      <input
                        id="logo"
                        ref="logoInput"
                        type="file"
                        hidden
                        @change="updateLogoPreview"
                      >
                    </div>
                  </div>
                </div>
                <p
                  v-show="form.errors.logo"
                  class="text-sm text-error mt-1"
                >
                  {{ form.errors.logo }}
                </p>
              </VCol>
            </VRow>
          </VWindowItem>

          <VWindowItem>
            <h5 class="text-h5">
              Set Your Password
            </h5>
            <p class="text-sm">
              Add your login details.
            </p>

            <VRow class="mb-4">
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
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                />
              </VCol>
            </VRow>
          </VWindowItem>
        </VWindow>

        <div class="d-flex flex-wrap justify-sm-space-between justify-center gap-x-4 gap-y-2 mt-2">
          <VBtn
            color="secondary"
            :disabled="currentStep === 0"
            variant="tonal"
            @click="prevStep"
          >
            <VIcon
              icon="tabler-arrow-left"
              start
              class="flip-in-rtl"
            />
            Back
          </VBtn>

          <VBtn
            v-if="filteredItems.length - 1 === currentStep"
            color="success"
            append-icon="tabler-check"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="handleUserAccountUpdate"
          >
            Create and Login Account
          </VBtn>

          <VBtn
            v-else
            @click="nextStep"
          >
            Next

            <VIcon
              icon="tabler-arrow-right"
              end
              class="flip-in-rtl"
            />
          </VBtn>
        </div>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">
@use "@core-scss/template/pages/page-auth.scss";

.illustration-image {
  block-size: 550px;
  inline-size: 248px;
}

.bg-image {
  inset-block-end: 0;
}
</style>
