<script setup>
defineProps({
  bookingTraveler: Object,
})
</script>

<template>
  <VCardText>
    <div
      class="mb-1 pa-4 rounded"
      style="background-color: #8692D014"
    >
      <div class="d-flex align-center mb-1">
        <VAvatar
          icon="tabler-phone"
          rounded
          class="pe-1"
        />
        Contact Details
      </div>
      <VDivider />
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VList class="transparent-bg">
            <VListItem
              title="Mobile Number"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.mobile_number ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Email"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.email_address ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Address"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.address ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Country"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.country ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="State"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.state ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Zip Code"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.zip_code ?? '-' }}
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
              title="Whatsapp"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.whatsapp_number ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Instagram Username"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.instagram_user_name ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Facebook Username"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.facebook_user_name ?? '-' }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Tiktok Username"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ bookingTraveler.tiktok_user_name ?? '-' }}
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
