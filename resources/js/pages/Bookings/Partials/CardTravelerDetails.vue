<script setup>
import CardTextPassportDetails from "@/pages/Bookings/Partials/CardTextPassportDetails.vue"
import CardTextContactDetails from "@/pages/Bookings/Partials/CardTextContactDetails.vue"
import CardTextEmergencyContactDetails from "@/pages/Bookings/Partials/CardTextEmergencyContactDetails.vue"
import CardTextFoodAndDrugs from "@/pages/Bookings/Partials/CardTextFoodAndDrugs.vue"
import CardTextHighlights from "@/pages/Bookings/Partials/CardTextHighlights.vue"
import FlightRow from "@/pages/Bookings/Partials/FlightRow.vue"

defineProps({
  bookingTravelers: Object,
})
</script>

<template>
  <VCard
    v-for="(bookingTraveler, index) of bookingTravelers"
    :key="index"
    class="mt-5"
  >
    <VCardText>
      <div class="d-flex flex-wrap align-center items-center">
        <div class="d-flex align-center">
          <VAvatar
            size="32"
            :color="bookingTraveler?.traveler_img ? '' : 'primary'"
            :class="bookingTraveler?.traveler_img ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!bookingTraveler?.traveler_img ? 'tonal' : undefined"
          >
            <VImg
              v-if="bookingTraveler?.traveler_img"
              :src="bookingTraveler?.profile_photo_url"
            />
            <span v-else>{{ avatarText(bookingTraveler?.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
          </VAvatar>
          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text-truncate text-primary">
              {{ bookingTraveler?.full_name }}
            </span>
            <span class="text-sm text-medium-emphasis">
              ID: {{ bookingTraveler?.id }}
            </span>
          </div>
        </div>

        <VSpacer />

        <div class="text-disabled">
          <VAvatar
            icon="tabler-shopping-cart"
            color="success"
            variant="tonal"
          />
          <span
            class="mx-5 font-weight-medium"
            style="color:black;"
          >05<sup>th</sup> Booking</span>

          <!--          <VIcon -->
          <!--            style="color: black" -->
          <!--            icon="tabler-arrow-big-up-lines" -->
          <!--          /> -->
        </div>
      </div>

      <VRow>
        <VCol>
          <VList density="compact">
            <VListItem :title="'First Name: ' + bookingTraveler.first_name ?? '-'" />
            <VListItem :title="'Last Name: ' + bookingTraveler.last_name ?? '-'" />
            <VListItem :title="'Language: ' + bookingTraveler.language ?? '-'" />
            <VListItem :title="'Gender: ' + bookingTraveler.gender ?? '-'" />
          </VList>
        </VCol>

        <VCol>
          <VList density="compact">
            <VListItem :title="'Call Name: ' + bookingTraveler.call_name ?? '-'" />
            <VListItem :title="'Birthday: ' + bookingTraveler.date_of_birth ?? '-'" />
          </VList>
        </VCol>
      </VRow>
    </VCardText>

    <CardTextPassportDetails :booking-traveler="bookingTraveler" />

    <CardTextContactDetails :booking-traveler="bookingTraveler" />

    <CardTextEmergencyContactDetails :booking-traveler="bookingTraveler" />

    <CardTextFoodAndDrugs :booking-traveler="bookingTraveler" />

    <CardTextHighlights :booking-traveler="bookingTraveler" />

    <FlightRow :booking-traveler="bookingTraveler" />
  </VCard>
</template>
