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
          icon="tabler-jewish-star"
          rounded
          class="pe-1"
        />
        Highlights
      </div>
      <VDivider />
      <VRow>
        <VCol cols="12">
          <VList class="transparent-bg">
            <VListItem
              title="Diet"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.diet ?? '-' }}
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
                {{ bookingTraveler.meditation ?? '-' }}
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
                {{ bookingTraveler.particular ?? '-' }}
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
                {{ bookingTraveler.note ?? '-' }}
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
