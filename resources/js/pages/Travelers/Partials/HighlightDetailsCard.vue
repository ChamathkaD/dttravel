<script setup>
const props = defineProps({
  traveler: Object,
})
</script>

<template>
  <VCard
    v-if="traveler"
    prepend-icon="tabler-jewish-star text-secondary"
    title="Highlights"
  >
    <VDivider />
    <VCardText>
      <VRow>
        <VCol cols="12">
          <VList>
            <VListItem
              title="Diet"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.diet }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Medication"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.medication }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Particular"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.particular }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Notes"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.note }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
