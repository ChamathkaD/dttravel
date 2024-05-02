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
          icon="tabler-e-passport"
          rounded
          class="pe-1"
        />
        Passport Details
      </div>
      <VDivider />
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VList class="transparent-bg">
            <VListItem
              title="Passport ID"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.passport_id ?? '-' }}
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
                {{ bookingTraveler.date_of_expire ?? '-' }}
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
              title="Date of Delivery"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.date_of_delivery ?? '-' }}
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
