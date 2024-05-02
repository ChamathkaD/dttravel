<script setup>
import { router, usePage } from "@inertiajs/vue3"
import { Notify } from "notiflix"

const props = defineProps({
  userData: {
    type: Object,
    required: false,
    default: () => ({
      first_name: null,
      last_name: null,
      email: null,
      role_name: null,
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  userRoles: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const errors = ref({})

const userData = ref(structuredClone(toRaw(props.userData)))

watch(props, () => {
  userData.value = structuredClone(toRaw(props.userData))
})

const filteredRoles = computed(() => {
  return props.userRoles.filter(role => role !== 'Super Administrator')
})

const onFormSubmit = () => {
  emit('submit', userData.value)

  router.put(route('users.update', userData.value.id), userData.value, {
    onSuccess: () => {
      Notify.success('User Updated Successfully!')
      emit('update:isDialogVisible', false)
    },
    onError: () => {
      errors.value = usePage().props.errors
    },
  })
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
    <VCard title="Edit User">
      <VForm @submit.prevent="onFormSubmit">
        <VCardText>
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <AppTextField
                v-model="userData.first_name"
                label="First Name"
                placeholder="John"
                :error-messages="errors ? errors.first_name : null"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <AppTextField
                v-model="userData.last_name"
                label="Last Name"
                persistent-hint
                placeholder="Doe"
                :error-messages="errors ? errors.last_name : null"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="userData.email"
                label="Email Address"
                placeholder="johndoe@email.com"
                :error-messages="errors ? errors.email : null"
                disabled
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="userData.role_name"
                :items="filteredRoles"
                placeholder="Select Role"
                label="User Type"
                :error-messages="errors ? errors.role_name : null"
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
