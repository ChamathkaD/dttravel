<script setup>
import { router, usePage } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import { ref } from "vue"
import slugify from "slugify"

const props = defineProps({
  categoryData: {
    type: Object,
    required: false,
    default: () => ({
      name: null,
      slug: null,
      photo: null,
      description: null,
      parent_category: null,
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  categories: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const errors = ref({})
const photoPreview = ref(null)
const photoInput = ref(null)

const categoryData = ref(structuredClone(toRaw(props.categoryData)))

watch(props, () => {
  categoryData.value = structuredClone(toRaw(props.categoryData))
})

watch(() => categoryData.value.name, newName => {
  // Update the slug using your slugify function or any other method
  categoryData.value.slug = slugify(newName, { lower: true })
})

const onFormSubmit = () => {
  emit('submit', categoryData.value)

  if (photoInput.value) {
    categoryData.value.photo = photoInput.value.files[0]
  }

  router.post(route('travel-categories.update', props.categoryData.id), {
    _method: 'put',
    name: categoryData.value.name,
    slug: categoryData.value.slug,
    parent_category: categoryData.value.parent_id,
    description: categoryData.value.description,
    photo: categoryData.value.photo,
  }, {
    onSuccess: () => {
      Notify.success('Category Updated Successfully!')
      emit('update:isDialogVisible', false)
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

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}
</script>

<template>
  <VDialog
    :model-value="isDialogVisible"
    max-width="600"
    persistent
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <!-- Dialog Content -->
    <VCard title="Edit Category">
      <VForm @submit.prevent="onFormSubmit">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="categoryData.name"
                label="Category Name"
                placeholder="Enter Category Title"
                :error-messages="errors ? errors.name : null"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="categoryData.slug"
                label="Slug"
                placeholder="Enter Slug"
                :error-messages="errors ? errors.slug : null"
              />
            </VCol>
            <VCol
              v-if="categoryData.parent_id !== null"
              cols="12"
            >
              <AppSelect
                v-model="categoryData.parent_id"
                :items="categories"
                clearable
                item-title="name"
                item-value="id"
                placeholder="Select Parent Category"
                label="Parent Category"
                :error-messages="errors ? errors.parent_category : null"
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
                  :color="categoryData.category_image_path ? '' : 'primary'"
                  :class="categoryData.category_image_path ? '' : 'v-avatar-light-bg primary--text'"
                  :variant="!categoryData.category_image_path ? 'tonal' : undefined"
                >
                  <VImg
                    v-if="categoryData.category_image_path"
                    :src="categoryData.category_image_url"
                  />

                  <VIcon
                    v-else
                    icon="tabler-category-2"
                  />
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

            <VCol cols="12">
              <AppTextarea
                v-model="categoryData.description"
                :error-messages="errors ? errors.description : null"
                label="Description"
                placeholder="Enter Category Description"
                auto-grow
                rows="4"
              />
            </VCol>
          </VRow>
        </VCardText>

        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <VBtn
            variant="text"
            @click="dialogModelValueUpdate(false)"
          >
            Cancel
          </VBtn>
          <VBtn
            class="bg-primary-700"
            type="submit"
          >
            Update
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
