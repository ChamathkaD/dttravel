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
          icon="tabler-milk"
          rounded
          class="pe-1"
        />
        Food & Drugs Status
      </div>
      <VDivider />

      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VList class="transparent-bg">
            <VListItem
              title="Food"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.drug_status ?? '-' }}
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
              title="Drugs"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.food_status ?? '-' }}
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
