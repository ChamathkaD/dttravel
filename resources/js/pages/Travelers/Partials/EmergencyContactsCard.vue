<script setup>
const props = defineProps({
  traveler: Object,
})
</script>

<template>
  <VCard
    v-if="traveler"
    prepend-icon="tabler-phone-outgoing text-secondary"
    title="Emergency Contacts"
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
              title="Emergency Contact 1"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.emergency_contact1 }}
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
              title="Emergency Contact 2"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.emergency_contact2 }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
