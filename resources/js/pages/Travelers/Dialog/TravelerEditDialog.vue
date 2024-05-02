<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import { countryCodes } from "@core/countryCodes"

const props = defineProps({
  travelerData: {
    type: Object,
    required: false,
    default: () => ({
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
      drug_status: [],
      food_status: null,
      diet: null,
      medication: null,
      particular: null,
      note: null,
    }),
  },
  countries: Object,
  travelers: Object,
  languages: Object,
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const errors = ref({})
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

const travelerData = ref(structuredClone(toRaw(props.travelerData)))

watch(props, () => {
  travelerData.value = structuredClone(toRaw(props.travelerData))

  if (travelerData.value.contact) {
    contactCodeInput.value = travelerData.value.contact.split(' ')[0]
    contactInput.value = travelerData.value.contact.split(' ').slice(1).join(' ')
  }

  if (travelerData.value.whatsapp) {
    whatsappContactCodeInput.value = travelerData.value.whatsapp.split(' ')[0]
    whatsappContactInput.value = travelerData.value.whatsapp.split(' ').slice(1).join(' ')
  }

  if (travelerData.value.emergency_contact1) {
    emergency1ContactCodeInput.value = travelerData.value.emergency_contact1.split(' ')[0]
    emergency1ContactInput.value = travelerData.value.emergency_contact1.split(' ').slice(1).join(' ')
  }

  if (travelerData.value.emergency_contact2) {
    emergency2ContactCodeInput.value = travelerData.value.emergency_contact2.split(' ')[0]
    emergency2ContactInput.value = travelerData.value.emergency_contact2.split(' ').slice(1).join(' ')
  }
})

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

const travelerRelationshipForm = ref([
  { traveler: null, traveler_relation: null, errors: {} },
])

const addRelationship = () => {
  travelerRelationshipForm.value.push({ traveler: null, traveler_relation: null, errors: {} })
}

const removeRelationship = index => {
  travelerRelationshipForm.value.splice(index, 1)
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

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const handleUpdatePersonalDetails = () => {
  if (photoInput.value) {
    travelerData.value.photo = photoInput.value.files[0]
  }

  router.post(route('travelers.update-personal-details', travelerData.value.id), {
    _method: 'put',
    first_name: travelerData.value.first_name,
    last_name: travelerData.value.last_name,
    call_name: travelerData.value.call_name,
    dob: travelerData.value.dob,
    gender: travelerData.value.gender,
    passport_id: travelerData.value.passport_id,
    date_of_delivery: travelerData.value.date_of_delivery,
    date_of_expire: travelerData.value.date_of_expire,
    language: travelerData.value.language,
    photo: travelerData.value.photo,
  }, {
    onSuccess: () => {
      currentStep.value++
      Notify.success('Traveler Personal Details Updated!')
    },
    onError: () => {
      errors.value = usePage().props.errors
    },
  })
}

const handleUpdateContactDetails = () => {

  if (contactInput.value && contactCodeInput.value) {
    travelerData.value.contact = contactCodeInput.value + ' ' + contactInput.value
  }

  if (whatsappContactInput.value && whatsappContactCodeInput.value) {
    travelerData.value.whatsapp = whatsappContactCodeInput.value + ' ' + whatsappContactInput.value
  }

  if (emergency1ContactInput.value && emergency1ContactCodeInput.value) {
    travelerData.value.emergency_contact1 = emergency1ContactCodeInput.value + ' ' + emergency1ContactInput.value
  }

  if (emergency2ContactInput.value && emergency2ContactCodeInput.value) {
    travelerData.value.emergency_contact2 = emergency2ContactCodeInput.value + ' ' + emergency2ContactInput.value
  }

  router.post(route('travelers.update-contact-details', travelerData.value.id), {
    _method: 'put',
    contact: travelerData.value.contact,
    whatsapp: travelerData.value.whatsapp,
    email: travelerData.value.email,
    address: travelerData.value.address,
    country: travelerData.value.country,
    state: travelerData.value.state,
    zip: travelerData.value.zip,
    instagram: travelerData.value.instagram,
    tiktok: travelerData.value.tiktok,
    facebook: travelerData.value.facebook,
    emergency_contact1: travelerData.value.emergency_contact1,
    emergency_contact2: travelerData.value.emergency_contact2,
  }, {
    onSuccess: () => {
      currentStep.value++
      Notify.success('Traveler Contact Details Updated!')
    },
    onError: () => {
      errors.value = usePage().props.errors
    },
  })
}

const handleUpdateHighlights = () => {
  router.post(route('travelers.update-highlight-details', travelerData.value.id), {
    _method: 'put',
    traveler_relationships: travelerRelationshipForm.value,
    drug_status: travelerData.value.drug_status,
    food_status: travelerData.value.food_status,
    diet: travelerData.value.diet,
    meditation: travelerData.value.meditation,
    particular: travelerData.value.particular,
    note: travelerData.value.note,
  }, {
    onSuccess: () => {
      Notify.success('Traveler Highlight Details Updated!')
      dialogModelValueUpdate(false)
      location.reload()
    },
    onError: () => {
      errors.value = usePage().props.errors
    },
  })
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
    <VCard title="Edit Traveler">
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
                  <VRow>
                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.first_name"
                        label="First Name"
                        placeholder="Jhon"
                        :error-messages="errors ? errors.first_name : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.last_name"
                        label="Last Name"
                        placeholder="Doe"
                        :error-messages="errors ? errors.last_name : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.call_name"
                        label="Call Name"
                        placeholder="JD"
                        :error-messages="errors ? errors.call_name : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        v-model="travelerData.dob"
                        label="Date of Birthday"
                        type="date"
                        :error-messages="errors ? errors.dob : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppSelect
                        v-model="travelerData.gender"
                        :items="genders"
                        label="Gender"
                        placeholder="Select Gender"
                        :error-messages="errors ? errors.gender : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.passport_id"
                        label="Passport ID"
                        placeholder="Passport ID"
                        :error-messages="errors ? errors.passport_id : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.date_of_delivery"
                        label="Date of Delivery"
                        type="date"
                        :error-messages="errors ? errors.date_of_delivery : null"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.date_of_expire"
                        label="Date of Expire"
                        type="date"
                        :error-messages="errors ? errors.date_of_expire : null"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppSelect
                        v-model="travelerData.language"
                        :items="languages"
                        item-title="value"
                        item-value="value"
                        label="Language"
                        placeholder="Select Language"
                        :error-messages="errors ? errors.language : null"
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

                        <VAvatar
                          v-show="!photoPreview"
                          rounded
                          size="100"
                          :color="travelerData.profile_photo_path ? '' : 'primary'"
                          :class="travelerData.profile_photo_path ? '' : 'v-avatar-light-bg primary--text'"
                          :variant="!travelerData.profile_photo_path ? 'tonal' : undefined"
                        >
                          <VImg
                            v-if="travelerData.profile_photo_path"
                            :src="travelerData.profile_photo_url"
                          />

                          <span
                            v-else
                            class="text-3xl"
                          >{{ avatarText(travelerData.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
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
                        v-show="errors"
                        class="text-sm text-error mt-1"
                      >
                        {{ errors.photo }}
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
                            :error="errors.whatsapp"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="whatsappContactInput"
                            label="&nbsp;"
                            :error="errors.whatsapp"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="errors.whatsapp"
                            class="text-sm text-error mt-1"
                          >
                            {{ errors.whatsapp }}
                          </p>
                        </VCol>
                      </VRow>
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="travelerData.email"
                        label="Email"
                        placeholder="yourname@mail.com"
                        :error-messages="errors ? errors.email : null"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="travelerData.address"
                        :error-messages="errors ? errors.address : null"
                        label="Address"
                        placeholder="Address"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppSelect
                        v-model="travelerData.country"
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
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.state"
                        :error-messages="errors ? errors.state : null"
                        label="State"
                        placeholder="State"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.zip"
                        :error-messages="errors ? errors.zip : null"
                        label="Zip Code"
                        placeholder="Zip Code"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.instagram"
                        :error-messages="errors ? errors.instagram : null"
                        label="Instagram Username"
                        placeholder="Instagram"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.tiktok"
                        :error-messages="errors ? errors.tiktok : null"
                        label="Tiktok Username"
                        placeholder="Tiktok"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      md="4"
                    >
                      <AppTextField
                        v-model="travelerData.facebook"
                        :error-messages="errors ? errors.facebook : null"
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
                            :error="errors.emergency_contact1"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="emergency1ContactInput"
                            label="&nbsp;"
                            :error="errors.emergency_contact1"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="errors.emergency_contact1"
                            class="text-sm text-error mt-1"
                          >
                            {{ errors.emergency_contact1 }}
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
                            :error="errors.emergency_contact2"
                            :items="countryCodes"
                          />
                        </VCol>
                        <VCol>
                          <AppTextField
                            v-model="emergency2ContactInput"
                            label="&nbsp;"
                            :error="errors.emergency_contact2"
                            placeholder="712565898"
                          />
                        </VCol>
                      </VRow>
                      <VRow no-gutters="">
                        <VCol>
                          <p
                            v-show="errors.emergency_contact2"
                            class="text-sm text-error mt-1"
                          >
                            {{ errors.emergency_contact2 }}
                          </p>
                        </VCol>
                      </VRow>
                    </VCol>
                  </VRow>
                </VWindowItem>

                <VWindowItem>
                  <VRow>
                    <VCol
                      v-if="travelerData.traveler_relations.length !== 0"
                      cols="12"
                    >
                      <VList lines="two">
                        <VListItem
                          v-for="item in travelerData.traveler_relations"
                          :key="item.id"
                          :title="item.traveler"
                          :subtitle="item.relation_type"
                        >
                          <template #prepend>
                            <VAvatar
                              color="secondary"
                              variant="tonal"
                            >
                              <VIcon
                                :size="26"
                                icon="tabler-hierarchy"
                              />
                            </VAvatar>
                          </template>
                        </VListItem>
                      </VList>
                    </VCol>

                    <template
                      v-for="(item, index) in travelerRelationshipForm"
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
                        v-model="travelerData.drug_status"
                        :error-messages="errors ? errors.drug_status : null"
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
                        v-model="travelerData.food_status"
                        :error-messages="errors ? errors.food_status : null"
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
                        v-model="travelerData.diet"
                        :error-messages="errors ? errors.diet : null"
                        label="Diet"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="travelerData.medication"
                        :error-messages="errors ? errors.medication : null"
                        label="Medication"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="travelerData.particular"
                        :error-messages="errors ? errors.particular : null"
                        label="Particular"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="travelerData.note"
                        :error-messages="errors ? errors.note : null"
                        label="Note"
                        placeholder="Type Here"
                        auto-grow
                        rows="2"
                      />
                    </VCol>
                  </VRow>
                </VWindowItem>
              </VWindow>

              <div class="d-flex flex-wrap justify-end gap-x-4 gap-y-2 mt-10">
                <VBtn
                  v-if="items.length - 3 === currentStep"
                  @click="handleUpdatePersonalDetails"
                >
                  Update Personal Details
                </VBtn>

                <VBtn
                  v-else-if="items.length - 2 === currentStep"
                  @click="handleUpdateContactDetails"
                >
                  Update Contact Details
                </VBtn>

                <VBtn
                  v-else
                  @click="handleUpdateHighlights"
                >
                  Update Highlights & Close
                </VBtn>
              </div>
            </VCard>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
