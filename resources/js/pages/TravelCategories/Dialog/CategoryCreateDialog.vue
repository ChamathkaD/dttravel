<script setup>
import { ref, watch } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import slugify from "slugify"

defineProps({
  categories: Object,
})

const isDialogVisible = ref(false)
const photoPreview = ref(null)
const photoInput = ref(null)

const nameInput = ref('')
const slugInput = ref('')

watch(nameInput, () => slugInput.value = slugify(nameInput.value, { lower: true }))

const form = useForm({
  name: nameInput.value,
  slug: slugInput.value,
  parent_category: null,
  description: null,
  photo: null,
})

watch(nameInput, newValue => {
  form.name = newValue
})

watch(slugInput, newValue => {
  form.slug = newValue
})

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

const handleCreateCategory = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  form.post(route('travel-categories.store'), {
    onSuccess: () => {
      isDialogVisible.value = false
      Notify.success('Category Added Successfully!')
      form.reset()
      clearPhotoFileInput()
      nameInput.value = ''
      slugInput.value = ''
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
  photoPreview.value = null
}
</script>

<template>
  <VDialog
    v-model="isDialogVisible"
    max-width="600"
    persistent
  >
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        class="bg-primary-700"
        prepend-icon="tabler-plus"
      >
        Add New Category
      </VBtn>
    </template>

    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

    <!-- Dialog Content -->
    <VCard
      title="Add New Category"
      subtitle="Lorem ipsum dolor sit amet, consectetur adipisicing elit"
    >
      <VForm @submit.prevent="handleCreateCategory">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="nameInput"
                label="Category Name"
                placeholder="Enter Category Title"
                :error-messages="form.errors.name"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="slugInput"
                label="Slug"
                placeholder="Enter Slug"
                :error-messages="form.errors.slug"
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="form.parent_category"
                :items="categories"
                clearable
                item-title="name"
                item-value="id"
                placeholder="Select Parent Category"
                label="Parent Category"
                :error-messages="form.errors.parent_category"
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
                        <span class="d-none d-sm-block">Upload Category Image</span>
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

            <VCol cols="12">
              <AppTextarea
                v-model="form.description"
                :error-messages="form.errors.description"
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
            @click="isDialogVisible = false"
          >
            Cancel
          </VBtn>
          <VBtn
            class="bg-primary-700"
            :class="{'opacity-50': form.processing}"
            :disabled="form.processing"
            type="submit"
          >
            Add New Category
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
