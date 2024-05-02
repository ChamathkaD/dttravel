<script setup>
import { ref } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  roles: Array,
})

const { hasRole } = usePermission()
const isDialogVisible = ref(false)

const filteredRoles = computed(() => {
  if (hasRole('Staff Member')) {
    return props.roles.filter(role => role === 'Travel Agent')
  }

  return props.roles.filter(role => role !== 'Super Administrator')
})

const form = useForm({
  first_name: null,
  last_name: null,
  email: null,
  role: route().params.roleName === 'agent' ? 'Travel Agent' : route().params.roleName === 'staff' ? 'Staff Member' : null,
})

const handleUserCreate = () => {
  form.post(route('users.store'), {
    onFinish: () => form.reset(),
    onSuccess: () => {
      isDialogVisible.value = false
      Notify.success('User Invited Successfully!')
      form.reset()
    },
  })
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
      >
        {{ hasRole('Staff Member') ? 'Create Agent' : 'Create User' }}
      </VBtn>
    </template>

    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

    <!-- Dialog Content -->
    <VCard title="Add New User">
      <VForm @submit.prevent="handleUserCreate">
        <VCardText>
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <AppTextField
                v-model="form.first_name"
                label="First Name"
                placeholder="John"
                :error-messages="form.errors.first_name"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <AppTextField
                v-model="form.last_name"
                label="Last Name"
                persistent-hint
                placeholder="Doe"
                :error-messages="form.errors.last_name"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="form.email"
                label="Email Address"
                placeholder="johndoe@email.com"
                :error-messages="form.errors.email"
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="form.role"
                :items="filteredRoles"
                placeholder="Select Role"
                name="select"
                label="User Type"
                :error-messages="form.errors.role"
                clearable
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
            Send User Invitation
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
