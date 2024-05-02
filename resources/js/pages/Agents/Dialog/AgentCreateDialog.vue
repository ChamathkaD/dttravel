<script setup>
import { ref } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import { countryCodes } from '@/@core/countryCodes'

defineProps({
  countries: Object,
})

const isDialogVisible = ref(false)
const currentStep = ref(0)
const photoPreview = ref(null)
const photoInput = ref(null)
const logoPreview = ref(null)
const logoInput = ref(null)

const contactInput = ref(null)
const contactCodeInput = ref(null)
const businessContactInput = ref(null)
const businessContactCodeInput = ref(null)

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
])

const form = useForm({
  first_name: null,
  last_name: null,
  email: null,
  contact: null,
  agent_commission_rate: null,
  photo: null,
  business_name: null,
  business_reg_no: null,
  business_contact: null,
  business_email: null,
  address: null,
  country: null,
  business_city: null,
  logo: null,
  role: "Travel Agent",
})

const handleSendLoginInvitation = () => {
  if (logoInput.value) {
    form.logo = logoInput.value.files[0]
  }

  if (businessContactInput.value && businessContactCodeInput.value) {
    form.business_contact = businessContactCodeInput.value + ' ' + businessContactInput.value
  }

  form.post(route('agents.store'), {
    onSuccess: () => {
      clearFileInputs()
      isDialogVisible.value = false
      currentStep.value = 0
      Notify.success('Sending Agent Login Invitation...')
      form.reset()
      businessContactInput.value = null
      businessContactCodeInput.value = null
      clearStepOneFormInputs()
    },
  })
}

const nextStep = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  if (contactInput.value && contactCodeInput.value) {
    form.contact = contactCodeInput.value + ' ' + contactInput.value
  }

  form.post(route('agents.step-one.store'), {
    onSuccess: () => {
      currentStep.value++
    },
  })
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

const clearFileInputs = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
    photoPreview.value = null
  }

  if (logoInput.value?.value) {
    logoInput.value.value = null
    logoPreview.value = null
  }
}

const clearStepOneFormInputs = () => {
  form.first_name = null
  form.last_name = null
  form.email = null
  form.contact = null
  form.agent_commission_rate = null
  contactInput.value = null
  contactCodeInput.value = null
}
</script>

<template>
  <VDialog
    v-model="isDialogVisible"
    max-width="800"
    persistent
  >
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        class="bg-primary-700"
      >
        Add New Agent
      </VBtn>
    </template>

    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

    <!-- Dialog Content -->
    <VCard title="Add New Agent">
      <VForm @submit.prevent="() => {}">
        <VCardText>
          <VRow
            no-gutters
            class="auth-wrapper"
          >
            <VCol
              cols="12"
              class="auth-card-v2 d-flex align-center justify-center pa-10"
            >
              <VCard
                flat
                class="mt-12 mt-sm-0"
              >
                <AppStepper
                  v-model:current-step="currentStep"
                  :items="items"
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
                          label="First Name"
                          placeholder="Jhon"
                          :error-messages="form.errors.first_name"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="form.last_name"
                          label="Last Name"
                          placeholder="Doe"
                          :error-messages="form.errors.last_name"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppTextField
                          v-model="form.email"
                          label="Email"
                          placeholder="yourname@mail.com"
                          :error-messages="form.errors.email"
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
                        <AppTextField
                          v-model="form.agent_commission_rate"
                          label="Agent Commission Rate (%)"
                          :error-messages="form.errors.agent_commission_rate"
                          placeholder="Commission Rate"
                        />
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
                              <div>
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
                                <p class="text-disabled text-sm mt-1">
                                  Allowed JPG or PNG, Max size of 1000px
                                </p>
                              </div>

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

                  <VWindowItem>
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
                          v-model="form.address"
                          :error-messages="form.errors.address"
                          label="Business Address"
                          placeholder="1234 Main St, New York, NY 10001, USA"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppSelect
                          v-model="form.country"
                          :error-messages="form.errors.country"
                          :items="countries"
                          item-title="name"
                          item-value="name"
                          placeholder="Select Country"
                          label="Country"
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
                              <div>
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
                                <p class="text-disabled text-sm mt-1">
                                  Allowed JPG or PNG, Max size of 1000px
                                </p>
                              </div>

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
                </VWindow>

                <div class="d-flex flex-wrap justify-sm-space-between justify-center gap-x-4 gap-y-2 mt-2">
                  <VBtn
                    color="dark"
                    :disabled="currentStep === 0"
                    variant="text"
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
                    v-if="items.length - 1 === currentStep"
                    append-icon="tabler-arrow-narrow-right"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="handleSendLoginInvitation"
                  >
                    Send agent login invitation
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
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
