<script setup>
import { ref } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"

const isDialogVisible = ref(false)

const form = useForm({
  first_name: null,
  last_name: null,
  email: null,
  agent_commission_rate: null,
})

const handleInviteAgent = () => {
  form.post(route('agents.invite'), {
    preserveState: true,
    onSuccess: () => {
      isDialogVisible.value = false
      Notify.success('Agent Invited Successfully!')
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
        class="text-primary-700"
        variant="text"
      >
        Send new agent invitation
      </VBtn>
    </template>

    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

    <!-- Dialog Content -->
    <VCard title="Invite New Agent">
      <VForm @submit.prevent="handleInviteAgent">
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
              <AppTextField
                v-model="form.agent_commission_rate"
                label="Agent Commission Rate (%)"
                :error-messages="form.errors.agent_commission_rate"
                placeholder="Commission Rate"
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
            Send Agent Invitation
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
