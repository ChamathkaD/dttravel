<script setup>
import { usePermission } from "@/composables/usePermission"

const { hasRole } = usePermission()
</script>

<template>
  <VRow>
    <VCol
      v-if="!hasRole('Staff Member')"
      cols="12"
      md="6"
    >
      <VCard height="180">
        <VCardText class="d-flex gap-y-2 flex-column">
          <VAvatar
            variant="tonal"
            color="primary"
            icon="tabler-currency-dollar"
            rounded
          />
          <h4 class="text-h4">
            Total Spend
          </h4>
          <div>
            <span class="text-primary text-h4 me-2">$2345</span>
            <span class="text-body-1">Credit Left</span>
            <p class="mb-0 text-base text-disabled">
              Account balance for next purchase
            </p>
          </div>
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <VCard height="180">
        <VCardText class="d-flex gap-y-2 flex-column">
          <VAvatar
            variant="tonal"
            color="success"
            icon="tabler-user-check"
            rounded
          />
          <h4 class="text-h4">
            Agent Name
          </h4>
          <div>
            <VChip
              label
              size="small"
              color="success"
            >
              Agent Number
            </VChip>
            <p class="mt-1 text-base text-disabled">
              ID: 2464694
            </p>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
