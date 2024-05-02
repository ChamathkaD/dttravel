<script setup>
import { router, useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"

const props = defineProps({
  user: Object,
})

const photoPreview = ref(null)
const photoInput = ref(null)

const form = useForm({
  _method: 'PUT',
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email,
  contact: props.user.contact,
  photo: null,
})

const updateProfileInformation = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  form.post(route('user-profile-information.update'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => {
      clearPhotoFileInput()
      location.reload()
      Notify.success('Profile Updated!')
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

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null
      clearPhotoFileInput()
      location.reload()
      Notify.success('Profile Photo Deleted!')
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Account Information">
        <VForm @submit.prevent="updateProfileInformation">
          <VCardText class="d-flex">
            <!-- 👉 Avatar -->
            <!-- Current Profile Photo -->
            <VAvatar
              v-show="!photoPreview"
              rounded
              size="100"
              class="me-6"
              :color="!props.user.profile_photo_path ? 'primary' : undefined"
              :variant="!props.user.profile_photo_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="user.profile_photo_path"
                :src="user.profile_photo_url"
              />

              <span
                v-else
                class="text-5xl font-weight-medium"
              >
                {{ avatarText(props.user.full_name.split(' ').slice(0, 2).join(' ')) }}
              </span>
            </VAvatar>

            <!-- New Profile Photo Preview -->
            <VAvatar
              v-show="photoPreview"
              rounded
              size="100"
              class="me-6"
              :image="photoPreview"
            />

            <!-- 👉 Upload Photo -->
            <div class="d-flex flex-column justify-center gap-4">
              <div class="d-flex flex-wrap gap-2">
                <VBtn
                  color="primary"
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

                <VBtn
                  v-if="user.profile_photo_path"
                  type="button"
                  color="secondary"
                  variant="tonal"
                  @click.prevent="deletePhoto"
                >
                  <span class="d-none d-sm-block">Remove Photo</span>
                  <VIcon
                    icon="tabler-refresh"
                    class="d-sm-none"
                  />
                </VBtn>
              </div>

              <p
                v-show="form.errors.photo"
                class="text-sm text-error mb-0"
              >
                {{ form.errors.photo }}
              </p>
            </div>
          </VCardText>

          <VDivider />

          <VCardText class="pt-2">
            <VRow class="mt-6">
              <!-- 👉 First Name -->
              <VCol
                md="6"
                cols="12"
              >
                <AppTextField
                  v-model="form.first_name"
                  placeholder="John"
                  label="First Name"
                  :error-messages="form.errors.first_name"
                />
              </VCol>

              <!-- 👉 Last Name -->
              <VCol
                md="6"
                cols="12"
              >
                <AppTextField
                  v-model="form.last_name"
                  placeholder="Doe"
                  label="Last Name"
                  :error-messages="form.errors.last_name"
                />
              </VCol>

              <!-- 👉 Email -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.email"
                  label="E-mail"
                  placeholder="johndoe@gmail.com"
                  type="email"
                  :error-messages="form.errors.email"
                  disabled
                />
              </VCol>

              <!-- 👉 Phone -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="form.contact"
                  label="Contact Number"
                  placeholder="+94 71 54 39 876"
                  :error-messages="form.errors.contact"
                />
              </VCol>

              <!-- 👉 Form Actions -->
              <VCol
                cols="12"
                class="d-flex flex-wrap justify-end gap-4"
              >
                <VBtn
                  type="submit"
                  :class="{'opacity-50': form.processing}"
                  :disabled="form.processing"
                >
                  Save changes
                </VBtn>
              </VCol>
            </VRow>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>
  </VRow>
</template>
