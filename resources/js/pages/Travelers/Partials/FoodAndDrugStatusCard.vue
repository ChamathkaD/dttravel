<script setup>
const props = defineProps({
  traveler: Object,
})
</script>

<template>
  <VCard
    v-if="traveler"
    prepend-icon="tabler-milk text-secondary"
    title="Food & Drugs Status"
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
              title="Food"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.food_status }}
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
              title="Drugs"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.drug_status }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
