<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3"
import { useDropZone, useFileDialog, useObjectUrl } from '@vueuse/core'
import { ref } from 'vue'
import { Notify } from "notiflix"
import ImageGallery from "@/pages/TravelPackage/Partials/ImageGallery.vue"
import MetaImage from "@/pages/TravelPackage/Partials/MetaImage.vue"

const props = defineProps({
  travelPackage: Object,
  destinations: Object,
  categories: Object,
  inclusions: Object,
  exclusions: Object,
  value_adds: Object,
  conditions: Object,
  inclusionIDs: Array,
  exclusionIDs: Array,
  valueAddsIDs: Array,
  conditionIDs: Array,
})

const optionCounter = ref(1)
const dropZoneRef = ref()
const fileData = ref([])
const { open, onChange } = useFileDialog({ accept: '.jpg,.png' })
const seoImageInput = ref(null)
const itineraryForm = ref()

const activeTab = ref('Day 1')
const inclusionActiveTab = ref('Inclusions')

const photoInput = ref(null)
const selectedFileName = ref('')

const inclusionTabsData = [
  {
    icon: 'tabler-cube',
    title: 'Inclusions',
    value: 'Inclusions',
  },
  {
    icon: 'tabler-car',
    title: 'Exclusions',
    value: 'Exclusions',
  },
  {
    icon: 'tabler-map-pin',
    title: 'Value Add',
    value: 'Value Add',
  },
]

function addNewOption() {
  form.itineraries.push({
    day: form.itineraries.length + 1,
    title: null,
    description: null,
    accommodation: null,
    food: null,
    location: null,
  })

  optionCounter.value = optionCounter.value + 1
  activeTab.value = optionCounter.value - 1
}

function onDrop(DroppedFiles) {
  DroppedFiles?.forEach(file => {

    if (fileData.value.length >= 5) {
      alert('Maximum file count reached (5 files allowed)')

      return
    }

    if (file.type.slice(0, 6) !== 'image/') {
      alert('Only image files are allowed')

      return
    }

    fileData.value.push({
      file,
      url: useObjectUrl(file).value ?? '',
    })
  })
}

onChange(selectedFiles => {

  if (!selectedFiles)
    return
  for (const file of selectedFiles) {

    if ((fileData.value.length + props.travelPackage.travel_package_images.length) >= 5) {

      Notify.failure('Maximum file count reached (5 files allowed)')

      return
    }

    if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
      Notify.failure('Only JPG and PNG image files are allowed')

      return
    }

    fileData.value.push({
      file,
      url: useObjectUrl(file).value ?? '',
    })
  }
})

useDropZone(dropZoneRef, { maxFiles: 5, onDrop })

const form = useForm({
  _method: 'PUT',
  travel_destination_id: props.travelPackage.travel_destination_id,
  travel_category_id: props.travelPackage.travel_category_id,
  name: props.travelPackage.name,
  description: props.travelPackage.description,
  adults_price: props.travelPackage.adults_price,
  child_price: props.travelPackage.child_price,
  discounted_price: props.travelPackage.discounted_price,
  tax: props.travelPackage.tax,
  min_pax: props.travelPackage.min_pax,
  max_pax: props.travelPackage.max_pax,
  is_charge_tax: props.travelPackage.is_charge_tax,
  top_rated: props.travelPackage.top_rated,
  meta_title: props.travelPackage.meta_title,
  meta_description: props.travelPackage.meta_description,
  meta_keyphrase: props.travelPackage.meta_keyphrase,
  meta_image: null,

  travel_package_images: null,

  itineraries: props.travelPackage.itineraries,

  inclusions: props.inclusionIDs,
  exclusions: props.exclusionIDs,
  valueAdds: props.valueAddsIDs,
  conditions: props.conditionIDs,
})

const handleUpdatePackage = () => {
  itineraryForm.value?.validate().then(valid => {
    if (valid.valid) {
      if (seoImageInput.value) {
        form.meta_image = seoImageInput.value.files[0]
      }

      if (fileData.value) {
        form.travel_package_images = fileData.value
      }

      form.post(route('travel-packages.update', props.travelPackage.id), {
        onFinish: () => form.reset(),
        onSuccess: () => {
          Notify.success('Package Updated')
          router.visit(route('travel-packages.index'))
        },
      })
    } else {
      Notify.failure('Please fill the form correctly!')
    }
  })
}

const inclusionForm = useForm({
  title: null,
})

const handleInclusionFormSubmit = () => {
  inclusionForm.post(route('travel-packages.inclusions.store'), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      inclusionForm.reset()
      Notify.success('Inclusion Added!')

      // getting the keys of the object as an array
      const keys = Object.keys(props.inclusions)

      // getting the last key from the array before adding new one
      const lastKey = keys[keys.length - 2]

      // using the last key to access the corresponding value in the object
      const lastIdData = props.inclusions[lastKey]

      form.inclusions.push(lastIdData.id + 1)
    },
  })
}

const exclusionForm = useForm({
  title: null,
})

const handleExclusionFormSubmit = () => {
  exclusionForm.post(route('travel-packages.exclusions.store'), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      exclusionForm.reset()
      Notify.success('Exclusion Added!')

      // getting the keys of the object as an array
      const keys = Object.keys(props.exclusions)

      // getting the last key from the array before adding new one
      const lastKey = keys[keys.length - 2]

      // using the last key to access the corresponding value in the object
      const lastIdData = props.exclusions[lastKey]

      form.exclusions.push(lastIdData.id + 1)
    },
  })
}

const valueAddForm = useForm({
  title: null,
  icon: null,
})

const handleValueAddFormSubmit = () => {
  if (photoInput.value) {
    valueAddForm.icon = photoInput.value.files[0]
  }

  valueAddForm.post(route('travel-packages.value-add.store'), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      valueAddForm.reset()
      photoInput.value = null
      selectedFileName.value = null
      Notify.success('valueAdd Added!')

      // getting the keys of the object as an array
      const keys = Object.keys(props.value_adds)

      // getting the last key from the array before adding new one
      const lastKey = keys[keys.length - 2]

      // using the last key to access the corresponding value in the object
      const lastIdData = props.value_adds[lastKey]

      form.valueAdds.push(lastIdData.id + 1)
    },
  })
}

const conditionForm = useForm({
  title: null,
})

const handleConditionFormSubmit = () => {
  conditionForm.post(route('travel-packages.conditions.store'), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      conditionForm.reset()
      Notify.success('Condition Added!')

      // getting the keys of the object as an array
      const keys = Object.keys(props.conditions)

      // getting the last key from the array before adding new one
      const lastKey = keys[keys.length - 2]

      // using the last key to access the corresponding value in the object
      const lastIdData = props.conditions[lastKey]

      form.conditions.push(lastIdData.id + 1)
    },
  })
}

const selectNewPhoto = () => {
  photoInput.value.click()
}

const onFileSelected = event => {
  const file = event.target.files[0]
  if (file) {
    selectedFileName.value = file.name
  }
}

const handleDeleteInclusion = id => {
  router.delete(route('travel-packages.inclusions.destroy', id), {
    preserveState: true,
    preserveScroll: true,
  })
}

const handleDeleteExclusion = id => {
  router.delete(route('travel-packages.exclusions.destroy', id), {
    preserveState: true,
    preserveScroll: true,
  })
}

const handleDeleteValueAdd = id => {
  router.delete(route('travel-packages.value-add.destroy', id), {
    preserveState: true,
    preserveScroll: true,
  })
}

const handleDeleteCondition = id => {
  router.delete(route('travel-packages.conditions.destroy', id), {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <Head title="Update Travel Package"/>
  <Breadcrumbs/>
  <div>
    <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">
          Update Travel Package
        </h4>
        <span>Orders placed across your store</span>
      </div>

      <div class="d-flex gap-4 align-center flex-wrap">
        <Link :href="route('travel-packages.index')">
          <VBtn
            variant="tonal"
            color="primary-500"
          >
            Discard
          </VBtn>
        </Link>
        <VBtn
          :class="{'opacity-50': form.processing}"
          :disabled="form.processing"
          @click="handleUpdatePackage"
        >
          Update Package
        </VBtn>
      </div>
    </div>
  </div>

  <VRow>
    <VCol md="8">
      <!-- 👉 Product Information -->
      <VCard
        class="mb-6"
        title="Product Information"
      >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="form.name"
                label="Name"
                placeholder="Produc Title"
                :error-messages="form.errors.name"
              />
            </VCol>

            <VCol>
              <span class="mb-1">Description (optional)</span>
              <TiptapEditor
                v-model="form.description"
                placeholder="Product Description"
                class="border rounded"
                :error-messages="form.errors.description"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>

      <!-- 👉 Media -->
      <VCard class="mb-6">
        <VCardItem>
          <template #title>
            Add 5 Images
          </template>
        </VCardItem>

        <VCardText>
          <ImageGallery :images="travelPackage.travel_package_images"/>
        </VCardText>

        <VCardText v-show="travelPackage.travel_package_images.length < 5">
          <div class="flex">
            <div class="w-full h-auto relative">
              <div
                ref="dropZoneRef"
                class="cursor-pointer"
                @click="() => open()"
              >
                <div
                  v-if="fileData.length === 0"
                  class="d-flex flex-column justify-center align-center gap-y-3 px-6 py-10 border-dashed drop-zone"
                  style="border: 1px lightgray;"
                >
                  <IconBtn
                    variant="tonal"
                    class="rounded-sm"
                  >
                    <VIcon icon="tabler-upload"/>
                  </IconBtn>
                  <div class="text-base text-high-emphasis font-weight-medium">
                    Drag and Drop Your Image Here.
                  </div>
                  <span class="text-disabled">or</span>

                  <VBtn variant="tonal">
                    Browse Images
                  </VBtn>
                </div>

                <div
                  v-else
                  class="d-flex justify-center align-center gap-3 pa-8 border-dashed drop-zone flex-wrap"
                >
                  <VRow class="match-height w-100">
                    <template
                      v-for="(item, index) in fileData"
                      :key="index"
                    >
                      <VCol
                        cols="12"
                        sm="4"
                      >
                        <VCard
                          :ripple="false"
                          border
                        >
                          <VCardText
                            class="d-flex flex-column"
                            @click.stop
                          >
                            <VImg
                              :src="item.url"
                              width="200px"
                              height="150px"
                              class="w-100 mx-auto"
                            />
                            <div class="mt-2">
                              <span class="clamp-text text-wrap">
                                {{ item.file.name }}
                              </span>
                              <span>
                                {{ item.file.size / 1000 }} KB
                              </span>
                            </div>
                          </VCardText>
                          <VSpacer/>
                          <VCardActions>
                            <VBtn
                              variant="outlined"
                              block
                              @click.stop="fileData.splice(index, 1)"
                            >
                              Remove File
                            </VBtn>
                          </VCardActions>
                        </VCard>
                      </VCol>
                    </template>
                  </VRow>
                </div>

                <p
                  v-show="form.errors.travel_package_images"
                  class="text-sm text-error mt-1"
                >
                  {{ form.errors.travel_package_images }}
                </p>
              </div>
            </div>
          </div>
        </VCardText>
      </VCard>


      <!-- 👉 Itinerary -->
      <VCard
        title="Itinerary *"
        class="inventory-card mb-6"
      >
        <VCardText>
          <VForm ref="itineraryForm">
            <VRow>
              <VCol
                cols="12"
                md="4"
              >
                <div class="pe-3">
                  <VTabs
                    v-model="activeTab"
                    direction="vertical"
                    color="primary"
                    class="v-tabs-pill"
                  >
                    <VTab
                      v-for="(item, index) in form.itineraries"
                      :key="index"
                      border
                      elevation="0"
                    >
                      <div class="text-truncate text-capitalize font-weight-medium text-start">
                        Day {{ index + 1 }}
                      </div>
                    </VTab>
                  </VTabs>
                </div>
              </VCol>

              <VCol
                cols="12"
                md="8"
              >
                <VWindow
                  v-model="activeTab"
                  class="w-100"
                  :touch="false"
                >
                  <VWindowItem
                    v-for="(item, index) in form.itineraries"
                    :key="index"
                    :value="`Day ${index + 1}`"
                  >
                    <div class="d-flex flex-column ps-3">
                      <VCol cols="12">
                        <AppTextField
                          v-model="item.title"
                          :placeholder="`Day ${index + 1} Title` "
                          :rules="[requiredValidator]"
                        />
                      </VCol>

                      <VCol>
                        <span class="mb-1">Description (optional)</span>
                        <TiptapEditor
                          v-model="item.description"
                          placeholder="Product Description"
                          class="border rounded"
                        />
                      </VCol>

                      <VCol cols="12">
                        <AppTextField
                          v-model="item.accommodation"
                          :label="`Day ${index + 1} Accommodation`"
                          :placeholder="`Day ${index + 1} Accommodation`"
                          :rules="[requiredValidator]"
                        />
                      </VCol>

                      <VCol cols="12">
                        <AppTextField
                          v-model="item.food"
                          :label="`Day ${index + 1} Food`"
                          :placeholder="`Day ${index + 1} Food`"
                          :rules="[requiredValidator]"
                        />
                      </VCol>

                      <VCol cols="12">
                        <AppTextField
                          v-model="item.location"
                          label="Location"
                          placeholder="Location"
                          :rules="[requiredValidator]"
                        />
                      </VCol>
                    </div>
                  </VWindowItem>
                </VWindow>
              </VCol>
            </VRow>
          </VForm>
          <VRow>
            <VCol class="text-right">
              <VBtn
                class="mt-6"
                @click="addNewOption"
              >
                Add another day
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>

      <!-- 👉 Inclusions & Exclusions -->
      <VCard
        title="Inclusions & Exclusions"
        class="inventory-card mb-6"
      >
        <VCardText>
          <VRow v-if="form.errors.inclusions || form.errors.exclusions || form.errors.valueAdds">
            <VCol class="mt-0 pt-0">
              <ul class="list-none">
                <li>
                  <span
                    v-show="form.errors.inclusions"
                    class="text-sm text-error"
                  >
                    {{ form.errors.inclusions }}
                  </span>
                </li>
                <li>
                  <span
                    v-show="form.errors.exclusions"
                    class="text-sm text-error"
                  >
                    {{ form.errors.exclusions }}
                  </span>
                </li>
                <li>
                  <span
                    v-show="form.errors.valueAdds"
                    class="text-sm text-error"
                  >
                    {{ form.errors.valueAdds }}
                  </span>
                </li>
              </ul>
            </VCol>
          </VRow>
          <VRow>
            <VCol
              cols="12"
              md="4"
            >
              <div class="pe-3">
                <VTabs
                  v-model="inclusionActiveTab"
                  direction="vertical"
                  color="primary"
                  class="v-tabs-pill"
                >
                  <VTab
                    v-for="(tab, index) in inclusionTabsData"
                    :key="index"
                  >
                    <VIcon
                      :icon="tab.icon"
                      class="me-2"
                    />
                    <div class="text-truncate font-weight-medium text-start">
                      {{ tab.title }}
                    </div>
                  </VTab>
                </VTabs>
              </div>
            </VCol>

            <VCol
              cols="12"
              md="8"
            >
              <VWindow
                v-model="inclusionActiveTab"
                class="w-100"
                :touch="false"
              >
                <VWindowItem value="Inclusions">
                  <div class="d-flex flex-column gap-y-4 ps-3">
                    <h5 class="text-h5">
                      Options
                    </h5>
                    <VCol
                      v-show="inclusions.length !== 0"
                      cols="12"
                    >
                      <VRow class="gap-x-10">
                        <VCheckbox
                          v-for="inclusion in inclusions"
                          :key="inclusion.id"
                          v-model="form.inclusions"
                          :label="inclusion.title"
                          :value="inclusion.id"
                        >
                          <template #append>
                            <VIcon
                              icon="tabler-x"
                              size="18"
                              color="error"
                              @click="handleDeleteInclusion(inclusion.id)"
                            />
                          </template>
                        </VCheckbox>
                      </VRow>
                    </VCol>

                    <VDivider
                      v-show="inclusions.length !== 0"
                      :vertical="$vuetify.display.smAndDown"
                    />
                    <VForm @submit.prevent="handleInclusionFormSubmit">
                      <VCol cols="12">
                        <AppTextField
                          v-model="inclusionForm.title"
                          label="Name of inclution"
                          placeholder="Name of inclution"
                          :error-messages="inclusionForm.errors.title"
                        />
                        <VBtn
                          class="mt-6"
                          variant="outlined"
                          type="submit"
                        >
                          Add New Include
                        </VBtn>
                      </VCol>
                    </VForm>
                  </div>
                </VWindowItem>

                <VWindowItem value="Exclusions">
                  <div class="d-flex flex-column gap-y-4 ps-3">
                    <h5 class="text-h5">
                      Options
                    </h5>
                    <VCol
                      v-show="exclusions.length !== 0"
                      cols="12"
                    >
                      <VRow class="gap-x-10">
                        <VCheckbox
                          v-for="exclusion in exclusions"
                          :key="exclusion.id"
                          v-model="form.exclusions"
                          :label="exclusion.title"
                          :value="exclusion.id"
                        >
                          <template #append>
                            <VIcon
                              icon="tabler-x"
                              size="18"
                              color="error"
                              @click="handleDeleteExclusion(exclusion.id)"
                            />
                          </template>
                        </VCheckbox>
                      </VRow>
                    </VCol>

                    <VDivider
                      v-show="exclusions.length !== 0"
                      :vertical="$vuetify.display.smAndDown"
                    />

                    <VForm @submit.prevent="handleExclusionFormSubmit">
                      <VCol cols="12">
                        <AppTextField
                          v-model="exclusionForm.title"
                          :error-messages="exclusionForm.errors.title"
                          label="Name of exclution"
                          placeholder="Name of exclution"
                        />
                        <VBtn
                          class="mt-6"
                          variant="outlined"
                          type="submit"
                        >
                          Add New Exclude
                        </VBtn>
                      </VCol>
                    </VForm>
                  </div>
                </VWindowItem>

                <VWindowItem value="Value Add">
                  <div class="d-flex flex-column gap-y-4 ps-3">
                    <h5 class="text-h5">
                      Options
                    </h5>
                    <VCol
                      v-show="value_adds.length !== 0"
                      cols="12"
                    >
                      <VRow class="gap-x-10">
                        <VCheckbox
                          v-for="valueAdd in value_adds"
                          :key="valueAdd.id"
                          v-model="form.valueAdds"
                          :label="valueAdd.title"
                          :value="valueAdd.id"
                        >
                          <template #append>
                            <VIcon
                              icon="tabler-x"
                              size="18"
                              color="error"
                              @click="handleDeleteValueAdd(valueAdd.id)"
                            />
                          </template>
                        </VCheckbox>
                      </VRow>
                    </VCol>

                    <VDivider
                      v-show="value_adds.length !== 0"
                      :vertical="$vuetify.display.smAndDown"
                    />

                    <VForm @submit.prevent="handleValueAddFormSubmit">
                      <VCol cols="12">
                        <AppTextField
                          v-model="valueAddForm.title"
                          label="Name of value add"
                          placeholder="Name of value add"
                          :error-messages="valueAddForm.errors.title"
                        />

                        <div class="d-block mt-6">
                          <label class="d-block text-sm mb-1">Icon (.png or .svg)</label>
                          <VBtn
                            variant="text"
                            color="black"
                            @click.prevent="selectNewPhoto"
                          >
                            <VIcon icon="tabler-photo me-2"/>
                            <span class="d-none d-sm-block">Choose Icon</span>
                          </VBtn>

                          <input
                            id="photo"
                            ref="photoInput"
                            accept="image/png, image/svg"
                            type="file"
                            hidden
                            @change="onFileSelected"
                          >

                          <VChip
                            v-if="selectedFileName"
                            color="primary"
                          >
                            {{ selectedFileName }}
                          </VChip>

                          <p
                            v-show="valueAddForm.errors.icon"
                            class="text-sm text-error mt-1"
                          >
                            {{ valueAddForm.errors.icon }}
                          </p>
                        </div>

                        <VBtn
                          class="mt-6"
                          variant="outlined"
                          type="sumbit"
                        >
                          Add New Value Add
                        </VBtn>
                      </VCol>
                    </VForm>
                  </div>
                </VWindowItem>
              </VWindow>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>

      <!-- 👉 Conditions -->
      <VCard
        title="Add Conditions"
        class="pb-10"
      >
        <VCardText>
          <div>
            <span
              v-show="form.errors.conditions"
              class="text-sm text-error"
            >
              {{ form.errors.conditions }}
            </span>

            <VCol
              v-show="conditions.length !== 0"
              cols="12"
            >
              <VCheckbox
                v-for="condition in conditions"
                :key="condition.id"
                v-model="form.conditions"
                :label="condition.title"
                :value="condition.id"
              >
                <template #append>
                  <VIcon
                    icon="tabler-x"
                    size="18"
                    color="error"
                    @click="handleDeleteCondition(condition.id)"
                  />
                </template>
              </VCheckbox>
            </VCol>
          </div>

          <VCol cols="12">
            <VForm @submit.prevent="handleConditionFormSubmit">
              <AppTextField
                v-model="conditionForm.title"
                label="Add new condition *"
                placeholder="Add new condition"
                class="mt-2"
                :error-messages="conditionForm.errors.title"
              />
              <VBtn
                variant="outlined"
                class="mt-6 float-end"
                type="submit"
              >
                Add new condition
              </VBtn>
            </VForm>
          </VCol>
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      md="4"
      cols="12"
    >
      <!-- 👉 Price -->
      <VCard
        title="Pricing"
        class="mb-6"
      >
        <VCardText>
          <AppTextField
            v-model="form.adults_price"
            label="Adult Price"
            placeholder="Adult Price"
            class="mb-6"
            :error-messages="form.errors.adults_price"
          />
          <AppTextField
            v-model="form.child_price"
            label="Child Price"
            placeholder="Child Price"
            class="mb-6"
            :error-messages="form.errors.child_price"
          />
          <AppTextField
            v-model="form.discounted_price"
            label="Discounted Price"
            placeholder="Discounted Price"
            class="mb-4"
            :error-messages="form.errors.discounted_price"
          />
          <AppTextField
            v-model="form.tax"
            label="Tax"
            placeholder="Tax"
            class="mb-4"
            :error-messages="form.errors.tax"
          />

          <VRow>
            <VCol cols="6">
              <AppTextField
                v-model="form.min_pax"
                label="Min Pax"
                placeholder="Min Pax"
                class="mb-4"
                type="number"
                min="0"
                :error-messages="form.errors.min_pax"
              />
            </VCol>
            <VCol cols="6">
              <AppTextField
                v-model="form.max_pax"
                label="Max Pax"
                placeholder="Max Pax"
                class="mb-4"
                type="number"
                min="0"
                :error-messages="form.errors.max_pax"
              />
            </VCol>
          </VRow>

          <VCheckbox
            v-model="form.is_charge_tax"
            label="Charge Tax on this product"
            :error-messages="form.errors.is_charge_tax"
          />

          <VDivider class="my-2"/>

          <div class="d-flex flex-raw align-center justify-space-between ">
            <span>Top Rated Package</span>
            <VSwitch
              v-model="form.top_rated"
              density="compact"
              :error-messages="form.errors.top_rated"
            />
          </div>
        </VCardText>
      </VCard>


      <!-- 👉 Organize -->
      <VCard
        title="Organize"
        class="mb-6"
      >
        <VCardText>
          <div class="d-flex flex-column gap-y-4">
            <div>
              <VLabel class="d-flex">
                <div class="d-flex text-sm justify-space-between w-100">
                  <div class="text-high-emphasis">
                    Destination
                  </div>
                  <!--                  <div class="text-primary cursor-pointer"> -->
                  <!--                    Add new Destination -->
                  <!--                  </div> -->
                </div>
              </VLabel>

              <AppSelect
                v-model="form.travel_destination_id"
                item-title="name"
                item-value="id"
                placeholder="Select Destination"
                :items="destinations"
                :error-messages="form.errors.travel_destination_id"
              />
            </div>
            <div>
              <VLabel class="d-flex">
                <div class="d-flex text-sm justify-space-between w-100">
                  <div class="text-high-emphasis">
                    Category
                  </div>
                  <!--                  <div class="text-primary cursor-pointer"> -->
                  <!--                    Add new Category -->
                  <!--                  </div> -->
                </div>
              </VLabel>

              <AppSelect
                v-model="form.travel_category_id"
                item-title="name"
                item-value="id"
                placeholder="Select Category"
                :items="categories"
                :error-messages="form.errors.travel_category_id"
              />
            </div>
          </div>
        </VCardText>
      </VCard>

      <!-- 👉 Meta -->
      <VCard title="Meta Update (SEO)">
        <VCardText>
          <div class="d-flex flex-column ">
            <AppTextField
              v-model="form.meta_title"
              label="Meta Title (60 max characters)"
              placeholder=""
              class="mb-6"
              :error-messages="form.errors.meta_title"
            />
            <AppTextField
              v-model="form.meta_description"
              label="Meta Description (160 max characters )"
              placeholder=""
              class="mb-6"
              :error-messages="form.errors.meta_description"
            />
            <AppTextField
              v-model="form.meta_keyphrase"
              label="Focus Keyphrase"
              placeholder=""
              class="mb-6"
              :error-messages="form.errors.meta_keyphrase"
            />

            <MetaImage
              v-if="travelPackage.meta_image"
              :travel-package="travelPackage"
            />

            <VFileInput
              v-if="travelPackage.meta_image === null"
              ref="seoImageInput"
              accept="image/*"
              label="Image source"
              :error-messages="form.errors.meta_image"
            />
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
