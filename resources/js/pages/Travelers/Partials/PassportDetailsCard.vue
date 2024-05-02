<script setup>
const props = defineProps({
  traveler: Object,
})
</script>

<template>
  <VCard
    v-if="traveler"
    prepend-icon="tabler-e-passport text-secondary"
    title="Passport Details"
  >
    <VDivider />
    <VCardText>
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VList>
            <VListItem
              title="Passport ID"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.passport_id }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Date of Expire"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.date_of_expire }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VList>
            <VListItem
              title="Date of Delivery"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.date_of_delivery }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
