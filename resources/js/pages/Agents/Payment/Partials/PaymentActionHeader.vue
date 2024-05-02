<script setup>
import { Confirm, Notify } from "notiflix"
import { Link, router } from "@inertiajs/vue3"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  agentPayment: Object,
})

const { hasRole } = usePermission()

const resolveStatusVariant = status => {
  if (status === 'unpaid')
    return {
      color: 'error',
    }

  if (status === 'paid')
    return {
      color: 'success',
    }
}

const handleUpdateStatus = () => {
  Confirm.show(
    'Set to Paid?',
    'Would you want set as paid this payment?',
    'Set Paid',
    'Close',
    () => {
      router.get(route("agents.payment.updateStatus", props.agentPayment.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Success!")
        },
      })
    })
}
</script>

<template>
  <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
    <div>
      <div class="d-flex gap-2 align-center mb-2 flex-wrap">
        <h4 class="text-h4">
          Agent Billing #{{ agentPayment.id }}
        </h4>
        <div class="d-flex gap-x-2">
          <VChip
            variant="tonal"
            :color="resolveStatusVariant(agentPayment.status).color"
            label
          >
            {{ agentPayment.status.toUpperCase() }}
          </VChip>
        </div>
      </div>
      <div>
        <span class="text-body-1">
          {{ agentPayment.billing_date }}
        </span>
      </div>
    </div>

    <div class="d-flex gap-4 align-center flex-wrap">
      <Link :href="route('agents.show-invoice', agentPayment.id)">
        <VBtn
          variant="outlined"
          color="dark"
          prepend-icon="tabler-printer"
        >
          Print
        </VBtn>
      </Link>

      <VBtn
        v-if="! hasRole('Travel Agent')"
        color="primary-800"
        :disabled="agentPayment.status === 'paid'"
        @click="handleUpdateStatus"
      >
        Paid
      </VBtn>
    </div>
  </div>
</template>
