<script setup>
import { ref } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import { countryCodes } from "@core/countryCodes"

defineProps({
  countries: Object,
  travelers: Object,
  languages: Object,
})

const isDialogVisible = ref(false)
const currentStep = ref(0)
const photoPreview = ref(null)
const photoInput = ref(null)
const counter = ref(1)

const contactInput = ref(null)
const contactCodeInput = ref(null)
const whatsappContactInput = ref(null)
const whatsappContactCodeInput = ref(null)
const emergency1ContactInput = ref(null)
const emergency1ContactCodeInput = ref(null)
const emergency2ContactInput = ref(null)
const emergency2ContactCodeInput = ref(null)


const genders = ref([
  'Male',
  'Female',
  'Rather Not Say',
])

const relationships = ref([
  { label: 'Father', value: 'Father' },
  { label: 'Mother', value: 'Mother' },
  { label: 'Brother', value: 'Brother' },
  { label: 'Sister', value: 'Sister' },
  { label: 'Wife', value: 'Wife' },
  { label: 'Husband', value: 'Husband' },
  { label: 'Son', value: 'Son' },
  { label: 'Daughter', value: 'Daughter' },
])

const foodStatus = ref([
  'Vegan',
  'Veg',
  'Non Veg',
])

const drugStatus = ref([
  'Non Alcoholic',
  'Alcohol & Smoke',
  'Only Smoke',
  'Only Alcohol',
])

const items = ref([
  {
    title: 'Personal Details',
    subtitle: 'Step One',
    icon: 'tabler-user',
  },
  {
    title: 'Contact Details',
    subtitle: 'Step Two',
    icon: 'tabler-device-landline-phone',
  },
  {
    title: 'Highlights',
    subtitle: 'Step Three',
    icon: 'tabler-jewish-star',
  },
])

const form = useForm({
  // step 1
  first_name: null,
  last_name: null,
  call_name: null,
  dob: null,
  gender: null,
  passport_id: null,
  date_of_delivery: null,
  date_of_expire: null,
  language: null,
  photo: null,

  // step 2
  contact: null,
  whatsapp: null,
  email: null,
  address: null,
  country: null,
  state: null,
  zip: null,
  instagram: null,
  tiktok: null,
  facebook: null,
  emergency_contact1: null,
  emergency_contact2: null,

  // step 3
  traveler_relationships: [
    { traveler: null, traveler_relation: null, errors: {} },
  ],
  drug_status: [],
  food_status: null,
  diet: null,
  medication: null,
  particular: null,
  note: null,
})

const addRelationship = () => {
  form.traveler_relationships.push({ traveler: null, traveler_relation: null, errors: {} })
}

const removeRelationship = index => {
  form.traveler_relationships.splice(index, 1)
}


const nextStep = () => {
  if (currentStep.value === 0) {

    if (photoInput.value) {
      form.photo = photoInput.value.files[0]
    }

    form.post(route('travelers.validate.personalFrom'), {
      onSuccess: () => {
        currentStep.value++
      },
    })
  }

  if (currentStep.value === 1) {

    if (contactInput.value && contactCodeInput.value) {
      form.contact = contactCodeInput.value + ' ' + contactInput.value
    }

    if (whatsappContactInput.value && whatsappContactCodeInput.value) {
      form.whatsapp = whatsappContactCodeInput.value + ' ' + whatsappContactInput.value
    }

    if (emergency1ContactInput.value && emergency1ContactCodeInput.value) {
      form.emergency_contact1 = emergency1ContactCodeInput.value + ' ' + emergency1ContactInput.value
    }

    if (emergency2ContactInput.value && emergency2ContactCodeInput.value) {
      form.emergency_contact2 = emergency2ContactCodeInput.value + ' ' + emergency2ContactInput.value
    }

    form.post(route('travelers.validate.contactFrom'), {
      onSuccess: () => {
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

const handleCreateTraveler = () => {
  form.post(route('travelers.store'), {
    onSuccess: () => {
      isDialogVisible.value = false
      currentStep.value = 0
      Notify.success('Traveler Added Successfully!')
      form.reset()
      clearPhotoFileInput()
      clearPrevStepsFormInputs()
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
  photoPreview.value = null
}

const clearPrevStepsFormInputs = () => {
  form.first_name = null
  form.last_name = null
  form.call_name = null
  form.dob = null
  form.gender = null
  form.passport_id = null
  form.date_of_delivery = null
  form.date_of_expire = null
  form.language = null
  form.contact = null
  form.whatsapp = null
  form.email = null
  form.address = null
  form.country = null
  form.state = null
  form.zip = null
  form.instagram = null
  form.facebook = null
  form.tiktok = null
  form.emergency_contact1 = null
  form.emergency_contact2 = null
  contactInput.value = null
  contactCodeInput.value = null
  whatsappContactInput.value = null
  whatsappContactCodeInput.value = null
  emergency1ContactInput.value = null
  emergency1ContactCodeInput.value = null
  emergency2ContactInput.value = null
  emergency2ContactCodeInput.value = null
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
        Add New Traveler
      </VBtn>
    </template>

    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

    <!-- Dialog Content -->
    <VCard title="Add New Traveler">
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
                  <VRow>
                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.first_name"
                        label="First Name *"
                        placeholder="Jhon"
                        :error-messages="form.errors.first_name"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.last_name"
                        label="Last Name *"
                        placeholder="Doe"
                        :error-messages="form.errors.last_name"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.call_name"
                        label="Call Name *"
                        placeholder="JD"
                        :error-messages="form.errors.call_name"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        v-model="form.dob"
                        label="Date of Birthday *"
                        type="date"
                        :error-messages="form.errors.dob"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppSelect
                        v-model="form.gender"
                        :items="genders"
                        label="Gender *"
                        placeholder="Select Gender"
                        :error-messages="form.errors.gender"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.passport_id"
                        label="Passport ID *"
                        placeholder="Passport ID"
                        :error-messages="form.errors.passport_id"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.date_of_delivery"
                        label="Date of Delivery *"
                        type="date"
                        :error-messages="form.errors.date_of_delivery"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.date_of_expire"
                        label="Date of Expire *"
                        type="date"
                        :error-messages="form.errors.date_of_expire"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppSelect
                        v-model="form.language"
                        :items="languages"
                        item-title="value"
                        item-value="value"
                        label="Language *"
                        placeholder="Select Language"
                        :error-messages="form.errors.language"
                        clearable
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
                  <VRow>
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
                            v-model="whatsappContactCodeInput"
                            label="Whatsapp Number"
                            placeholder="Code"
                            :error="form.errors.whatsapp"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="whatsappContactInput"
                            label="&nbsp;"
                            :error="form.errors.whatsapp"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="form.errors.whatsapp"
                            class="text-sm text-error mt-1"
                          >
                            {{ form.errors.whatsapp }}
                          </p>
                        </VCol>
                      </VRow>
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="form.email"
                        label="Email"
                        placeholder="yourname@mail.com"
                        :error-messages="form.errors.email"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="form.address"
                        :error-messages="form.errors.address"
                        label="Address"
                        placeholder="Address"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
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
                      md="4"
                    >
                      <AppTextField
                        v-model="form.state"
                        :error-messages="form.errors.state"
                        label="State"
                        placeholder="State"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.zip"
                        :error-messages="form.errors.zip"
                        label="Zip Code"
                        placeholder="Zip Code"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.instagram"
                        :error-messages="form.errors.instagram"
                        label="Instagram Username"
                        placeholder="Instagram"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.tiktok"
                        :error-messages="form.errors.tiktok"
                        label="Tiktok Username"
                        placeholder="Tiktok"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="form.facebook"
                        :error-messages="form.errors.facebook"
                        label="Facebook Username"
                        placeholder="Facebook"
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
                            v-model="emergency1ContactCodeInput"
                            label="Emergency Contact 1"
                            placeholder="Code"
                            :error="form.errors.emergency_contact1"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="emergency1ContactInput"
                            label="&nbsp;"
                            :error="form.errors.emergency_contact1"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="form.errors.emergency_contact1"
                            class="text-sm text-error mt-1"
                          >
                            {{ form.errors.emergency_contact1 }}
                          </p>
                        </VCol>
                      </VRow>
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
                            v-model="emergency2ContactCodeInput"
                            label="Emergency Contact 2"
                            placeholder="Code"
                            :error="form.errors.emergency_contact2"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="emergency2ContactInput"
                            label="&nbsp;"
                            :error="form.errors.emergency_contact2"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="form.errors.emergency_contact2"
                            class="text-sm text-error mt-1"
                          >
                            {{ form.errors.emergency_contact2 }}
                          </p>
                        </VCol>
                      </VRow>
                    </VCol>
                  </VRow>
                </VWindowItem>

                <VWindowItem>
                  <VRow>
                    <template
                      v-for="(item, index) in form.traveler_relationships"
                      :key="index"
                    >
                      <VCol
                        cols="12"
                        md="6"
                      >
                        <AppSelect
                          v-model="item.traveler"
                          :error-messages="item.errors.traveler"
                          :items="travelers"
                          item-title="full_name"
                          item-value="id"
                          placeholder="Choose"
                          label="Select Traveler"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="4"
                      >
                        <AppSelect
                          v-model="item.traveler_relation"
                          :error-messages="item.errors.traveler_relation"
                          :items="relationships"
                          placeholder="Choose"
                          item-title="label"
                          item-value="value"
                          label="Relationship"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        md="2"
                        class="d-flex align-center text-center"
                      >
                        <IconBtn
                          class="mt-md-6"
                          @click="removeRelationship(index)"
                        >
                          <VIcon
                            v-show="index !== 0"
                            size="20"
                            icon="tabler-x"
                          />
                        </IconBtn>
                      </VCol>
                    </template>

                    <VCol cols="12">
                      <VBtn
                        variant="text"
                        prepend-icon="tabler-plus"
                        @click="addRelationship"
                      >
                        Add More Relationship
                      </VBtn>
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <VRadioGroup
                        v-model="form.drug_status"
                        :error-messages="form.errors.drug_status"
                        label="Drug Status"
                        class="mb-4"
                      >
                        <VRadio
                          v-for="status in drugStatus"
                          :key="status"
                          :label="status"
                          :value="status"
                        />
                      </VRadioGroup>
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <VRadioGroup
                        v-model="form.food_status"
                        :error-messages="form.errors.food_status"
                        label="Food Status"
                        class="mb-4"
                      >
                        <VRadio
                          v-for="status in foodStatus"
                          :key="status"
                          :label="status"
                          :value="status"
                        />
                      </VRadioGroup>
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="form.diet"
                        :error-messages="form.errors.diet"
                        label="Diet"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="form.medication"
                        :error-messages="form.errors.medication"
                        label="Medication"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="form.particular"
                        :error-messages="form.errors.particular"
                        label="Particular"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="form.note"
                        :error-messages="form.errors.note"
                        label="Note"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>
                  </VRow>
                </VWindowItem>
              </VWindow>

              <div class="d-flex flex-wrap justify-sm-space-between justify-center gap-x-4 gap-y-2 mt-10">
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
                  @click="handleCreateTraveler"
                >
                  Add New Traveler
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
    </VCard>
  </VDialog>
</template>
