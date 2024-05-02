<script setup>
defineProps({
  bookingTraveler: Object,
})
</script>

<template>
  <VCardText>
    <div
      class="my-class mb-1 pa-4 rounded"
      style="background-color: #8692D014"
    >
      <div class="d-flex align-center mb-1">
        <VAvatar
          icon="tabler-phone-outgoing"
          rounded
          class="pe-1"
        />
        Emergency Contacts
      </div>
      <VDivider />
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VList class="transparent-bg">
            <VListItem
              title="Emergency Contact 1"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.emergency_contact1 ?? '-' }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VList class="transparent-bg">
            <VListItem
              title="Emergency Contact 2"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.emergency_contact2 ?? '-' }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </div>
  </VCardText>
</template>

<style scoped>
.transparent-bg {
  background-color: transparent;
}
</style>
