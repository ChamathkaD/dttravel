<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import FlightRow from "@/pages/Bookings/Partials/FlightRow.vue"
</script>

<template>
  <VCard class="mt-5">
    <VCardText>
      <div class="d-flex flex-wrap align-center">
        <VAvatar
          :image="avatar1"
          size="30"
          class="me-4"
        />

        <div>
          <div
            class="font-weight-medium"
            style="color:#000;"
          >
            Traveler Name
          </div>
          <div>ID #47389</div>
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

          <VIcon
            style="color: black"
            icon="tabler-arrow-big-down-lines"
          />
        </div>
      </div>

      <VRow>
        <VCol>
          <VList density="compact">
            <VListItem title="Call Name: Tim" />
            <VListItem title="Age: 24" />
            <VListItem title="Language: English" />
            <VListItem title="Gender: Male" />
          </VList>
        </VCol>

        <VCol>
          <VList density="compact">
            <VListItem title="Call Name: Tim" />
            <VListItem title="Age: 24" />
            <VListItem title="Language: English" />
            <VListItem title="Gender: Male" />
          </VList>
        </VCol>
      </VRow>
    </VCardText>

    <FlightRow />
  </VCard>
</template>
