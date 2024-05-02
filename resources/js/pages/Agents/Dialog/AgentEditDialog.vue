<script setup>
import { router, usePage } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import { ref, watch } from "vue"
import { countryCodes } from "@core/countryCodes"

const props = defineProps({
  agentData: {
    type: Object,
    required: false,
    default: () => ({
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
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  countries: Object,
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const errors = ref({})
const currentStep = ref(0)
const photoPreview = ref(null)
const photoInput = ref(null)
const logoPreview = ref(null)
const logoInput = ref(null)

const contactInput = ref(null)
const contactCodeInput = ref(null)
const businessContactInput = ref(null)
const businessContactCodeInput = ref(null)

const agentData = ref(structuredClone(toRaw(props.agentData)))

watch(props, () => {
  agentData.value = structuredClone(toRaw(props.agentData))

  if (agentData.value.contact) {
    contactCodeInput.value = agentData.value.contact.split(' ')[0]
    contactInput.value = agentData.value.contact.split(' ').slice(1).join(' ')
  }

  if (agentData.value.business_contact) {
    businessContactCodeInput.value = agentData.value.business_contact.split(' ')[0]
    businessContactInput.value = agentData.value.business_contact.split(' ').slice(1).join(' ')
  }
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
])

const handleUpdateAgentAccount = () => {
  if (photoInput.value) {
    agentData.value.photo = photoInput.value.files[0]
  }

  if (contactInput.value && contactCodeInput.value) {
    agentData.value.contact = contactCodeInput.value + ' ' + contactInput.value
  }

  router.post(route('agents.account.update', agentData.value.id), {
    _method: 'put',
    first_name: agentData.value.first_name,
    last_name: agentData.value.last_name,
    email: agentData.value.email,
    contact: agentData.value.contact,
    agent_commission_rate: agentData.value.agent_commission_rate,
    photo: agentData.value.photo,
  }, {
    onSuccess: () => {
      currentStep.value++
      Notify.success('Agent Account Information Updated!')
    },
    onError: () => {
      errors.value = usePage().props.errors
    },
  })
}

const handleUpdateAgentBusiness = () => {
  if (logoInput.value) {
    agentData.value.logo = logoInput.value.files[0]
  }

  if (businessContactInput.value && businessContactCodeInput.value) {
    agentData.value.business_contact = businessContactCodeInput.value + ' ' + businessContactInput.value
  }

  router.post(route('agents.business.update', agentData.value.id), {
    _method: 'put',
    business_name: agentData.value.business_name,
    business_reg_no: agentData.value.business_reg_no,
    business_contact: agentData.value.business_contact,
    business_email: agentData.value.business_email,
    address: agentData.value.address,
    country: agentData.value.country,
    business_city: agentData.value.business_city,
    logo: agentData.value.logo,
  }, {
    onSuccess: () => {
      Notify.success('Agent Business Information Updated!')
      dialogModelValueUpdate(false)
    },
    onError: () => {
      errors.value = usePage().props.errors
    },
  })
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

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}
</script>

<template>
  <VDialog
    :model-value="isDialogVisible"
    max-width="800"
    persistent
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <!-- Dialog Content -->
    <VCard title="Edit Agent">
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
                        v-model="agentData.first_name"
                        label="First Name"
                        placeholder="Jhon"
                        :error-messages="errors ? errors.first_name : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        v-model="agentData.last_name"
                        label="Last Name"
                        placeholder="Doe"
                        :error-messages="errors ? errors.last_name : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        v-model="agentData.email"
                        label="Email"
                        placeholder="yourname@mail.com"
                        :error-messages="errors ? errors.email : null"
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
                            :error="errors.contact"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="contactInput"
                            label="&nbsp;"
                            :error="errors.contact"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="errors.contact"
                            class="text-sm text-error mt-1"
                          >
                            {{ errors.contact }}
                          </p>
                        </VCol>
                      </VRow>
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="agentData.agent_commission_rate"
                        label="Agent Commission Rate (%)"
                        :error-messages="errors ? errors.agent_commission_rate : null"
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
                          class="me-6 v-avatar-light-bg"
                          color="primary"
                          :image="photoPreview"
                        />

                        <VAvatar
                          v-show="!photoPreview"
                          rounded
                          size="100"
                          :color="agentData.profile_photo_path ? '' : 'primary'"
                          :class="agentData.profile_photo_path ? '' : 'v-avatar-light-bg primary--text'"
                          :variant="!agentData.profile_photo_path ? 'tonal' : undefined"
                        >
                          <VImg
                            v-if="agentData.profile_photo_path"
                            :src="agentData.profile_photo_url"
                          />

                          <span
                            v-else
                            class="text-3xl"
                          >{{ avatarText(agentData.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
                        </VAvatar>

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
                        v-show="errors.photo"
                        class="text-sm text-error mt-1"
                      >
                        {{ errors.photo }}
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
                        v-model="agentData.business_name"
                        :error-messages="errors ? errors.business_name : null"
                        label="Business Name"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        v-model="agentData.business_reg_no"
                        :error-messages="errors ? errors.business_reg_no : null"
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
                            :error="errors.business_contact"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="businessContactInput"
                            :error="errors.business_contact"
                            label="&nbsp;"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="errors.business_contact"
                            class="text-sm text-error mt-1"
                          >
                            {{ errors.business_contact }}
                          </p>
                        </VCol>
                      </VRow>
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        v-model="agentData.business_email"
                        :error-messages="errors ? errors.business_email : null"
                        type="email"
                        label="Company Contact Email"
                        placeholder="businessname@mail.com"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="agentData.address"
                        :error-messages="errors ? errors.address : null"
                        label="Business Address"
                        placeholder="1234 Main St, New York, NY 10001, USA"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppSelect
                        v-model="agentData.country"
                        :error-messages="errors ? errors.country : null"
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
                        v-model="agentData.business_city"
                        :error-messages="errors ? errors.business_city : null"
                        label="City"
                        placeholder="New York"
                      />
                    </VCol>

                    <VCol cols="12">
                      <div class="d-flex gap-6">
                        <!-- New Logo Preview -->
                        <VAvatar
                          v-show="logoPreview"
                          rounded
                          size="100"
                          class="me-6"
                          :image="logoPreview"
                        />

                        <VAvatar
                          v-show="!logoPreview"
                          rounded
                          size="100"
                          class="me-6"
                          variant="tonal"
                        >
                          <VImg
                            v-if="agentData.business_logo_path"
                            :src="agentData.business_logo_url"
                          />

                          <VIcon
                            v-else
                            icon="tabler-photo"
                            size="40"
                          />
                        </VAvatar>

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
                        v-show="errors.logo"
                        class="text-sm text-error mt-1"
                      >
                        {{ errors.logo }}
                      </p>
                    </VCol>
                  </VRow>
                </VWindowItem>
              </VWindow>

              <div class="d-flex flex-wrap justify-sm-end justify-center gap-x-4 gap-y-2 mt-5">
                <VBtn
                  v-if="items.length - 1 === currentStep"
                  @click="handleUpdateAgentBusiness"
                >
                  Update Business Details
                </VBtn>

                <VBtn
                  v-else
                  @click="handleUpdateAgentAccount"
                >
                  Update Account Details
                </VBtn>
              </div>
            </VCard>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
