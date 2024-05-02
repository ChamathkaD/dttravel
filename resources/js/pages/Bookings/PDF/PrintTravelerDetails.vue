<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { Head } from "@inertiajs/vue3"
import CardTextEmergencyContactDetails from "@/pages/Bookings/Partials/CardTextEmergencyContactDetails.vue"
import CardTextHighlights from "@/pages/Bookings/Partials/CardTextHighlights.vue"
import CardTextContactDetails from "@/pages/Bookings/Partials/CardTextContactDetails.vue"
import FlightRow from "@/pages/Bookings/Partials/FlightRow.vue"
import CardTextFoodAndDrugs from "@/pages/Bookings/Partials/CardTextFoodAndDrugs.vue"
import CardTextPassportDetails from "@/pages/Bookings/Partials/CardTextPassportDetails.vue"
import logo from "@images/logo.svg"

const props = defineProps({
  booking: Object,
  agent: Object,
  traveler: Object,
})

const resolvePaymentStatus = status => {
  if (status === 'Paid')
    return {
      text: 'Paid',
      color: 'success',
    }
  if (status === 'Pending')
    return {
      text: 'Pending',
      color: 'warning',
    }
  if (status === 'Unpaid')
    return {
      text: 'Unpaid',
      color: 'error',
    }
  if (status === 'Partial')
    return {
      text: 'Partial',
      color: 'primary',
    }
}

const resolveStatus = status => {
  if (status === 'InProgress')
    return {
      text: 'InProgress',
      color: 'info',
    }
  if (status === 'Completed')
    return {
      text: 'Completed',
      color: 'success',
    }
  if (status === 'Cancel')
    return {
      text: 'Cancel',
      color: 'error',
    }
}

const travelPackage = [
  {
    packageName: props.booking.package.name,
    subtitle: 'ID: ' + props.booking.package.id,
    adultPrice: 'Adults - $' + props.booking.package.adults_price,
    childPrice: 'Child - $' + props.booking.package.child_price,
    adultPax: props.booking.adult_count ?? '-',
    childPax: props.booking.child_count ?? '-',
    adultTotal: props.booking.total_adults_price ?? '-',
    childTotal: props.booking.total_child_price ?? '-',
  },
]

const printInvoice = () => {
  window.print()
}
</script>

<template>
  <Head title="Booking Invoice" />
  <section>
    <VRow class="d-flex justify-center">
      <VCol
        cols="12"
        md="9"
        class="d-print-none d-flex justify-end"
      >
        <VBtn
          variant="tonal"
          color="primary"
          class="mb-2"
          @click="printInvoice"
        >
          Print
        </VBtn>
      </VCol>
      <VCol
        cols="12"
        md="9"
      >
        <VCard class="mb-2">
          <!-- SECTION Header -->
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row">
            <!-- 👉 Left Content -->
            <div class="ma-sm-4">
              <div class="d-flex align-center">
                <!-- 👉 Logo -->
                <VNodeRenderer
                  :nodes="themeConfig.app.logoFull"
                  class="me-3"
                />
              </div>

              <!-- 👉 Address -->
              <div class="text-lg font-weight-medium">
                DT Travels (Pvt) Ltd
              </div>
              <div>
                3A, Meegahawatte Road, Delkanda, Nugegoda - Sri Lanka
              </div>
              <div>
                +94 112 804 336 | +94 77 630 7625
              </div>
              <div>
                enquiries-dttravels@outlook.com
              </div>
            </div>

            <!-- 👉 Right Content -->
            <div class="ma-sm-4 text-right">
              <!-- 👉 Invoice ID -->
              <h6 class="font-weight-medium text-h4">
                Booking #{{ booking.id }}
              </h6>

              <!-- 👉 Issue Date -->
              <div>
                <span>Booking Issues: </span>
                <span>{{ new Date(booking.created_at).toLocaleDateString('en-GB') }}</span>
              </div>

              <!-- 👉 Travel Date -->
              <div>
                <span>Travel Date: </span>
                <span>{{ new Date(booking.travel_date).toLocaleDateString('en-GB') }}</span>
              </div>

              <div class="d-flex justify-end gap-x-2 mt-1">
                <VChip
                  variant="tonal"
                  :color="resolvePaymentStatus(booking.payment_status)?.color"
                  label
                >
                  {{ resolvePaymentStatus(booking.payment_status)?.text }}
                </VChip>
                <VChip
                  variant="tonal"
                  :color="resolveStatus(booking.status)?.color"
                  label
                >
                  {{ resolveStatus(booking.status)?.text }}
                </VChip>
              </div>
            </div>
          </VCardText>
          <!-- !SECTION -->

          <VDivider />

          <!--          👉 Payment Details -->
          <VCardText
            v-if="agent"
            class="d-flex justify-space-between flex-wrap flex-column flex-sm-row print-row"
          >
            <div class="ma-sm-4">
              <h6 class="text-base font-weight-medium mb-1">
                Agent Details:
              </h6>
              <p class="mb-1">
                Company Name:
                {{ agent.business_name }}
              </p>
              <p class="mb-1">
                Agent Name: {{ agent.full_name }}
                <span
                  v-if="agent"
                  class="ml-6"
                >
                  Agent ID:
                  {{ agent.id }}
                </span>
              </p>
            </div>
          </VCardText>

          <VCardText
            v-if="!agent"
            class="d-flex justify-space-between flex-wrap flex-column flex-sm-row print-row"
          >
            <div class="ma-sm-4">
              <h6 class="text-base font-weight-medium mb-1">
                Traveler Details:
              </h6>
              <p class="mb-1">
                Traveler ID:
                {{ traveler.id }}
              </p>
              <p class="mb-1">
                Traveler Name: {{ traveler.full_name }}
              </p>
              <p class="mb-1">
                Mobile:
                {{ traveler.contact }}
              </p>
              <p class="mb-0">
                Email:
                {{ traveler.email }}
              </p>
            </div>
          </VCardText>

          <VCardText class="print-row">
            <div class="ms-sm-4">
              <h6 class="text-base font-weight-bold mb-2">
                Flight Details
              </h6>
            </div>
            <VList lines="two">
              <template
                v-for="(bookingTraveler, index) of booking?.booking_travelers"
                :key="index"
              >
                <VListItem>
                  <VListItemTitle>
                    {{ bookingTraveler.flight_number ?? '-' }}
                  </VListItemTitle>
                  <VListItemSubtitle class="mt-1">
                    <span class="text-disabled">{{ bookingTraveler.flight_date ?? '-' }}</span>
                  </VListItemSubtitle>

                  <template #append>
                    <div
                      class="d-flex pe-6"
                      style="border-right: 1px dashed lightgray"
                    >
                      <VIcon
                        icon="tabler-plane-departure"
                        class="me-3"
                      />
                      <div>
                        <div class="text-body-1 font-weight-medium">
                          {{ bookingTraveler.dispatcher_time ?? '-' }}
                        </div>
                        <span class="text-sm text-disabled">{{ bookingTraveler.dispatcher_airport ?? '-' }}</span>
                      </div>
                    </div>

                    <div class="d-flex ms-5">
                      <VIcon
                        icon="tabler-plane-arrival"
                        class="me-3"
                      />
                      <div>
                        <div class="text-body-1 font-weight-medium">
                          {{ bookingTraveler.arrival_time ?? '-' }}
                        </div>
                        <span class="text-sm text-disabled">{{ bookingTraveler.arrival_airport ?? '-' }}</span>
                      </div>
                    </div>
                  </template>
                </VListItem>
                <VDivider v-if="index !== booking.booking_travelers.length - 1" />
              </template>
            </VList>
          </VCardText>

          <VCard
            v-for="(bookingTraveler, index) of booking.booking_travelers"
            :key="index"
            class="print-row"
            elevation="0"
          >
            <VCardText>
              <div
                v-if="agent"
                class="d-flex flex-wrap align-center items-center ms-4"
              >
                <VAvatar
                  size="32"
                  :color="agent.profile_photo_path ? '' : 'primary'"
                  :class="agent.profile_photo_path ? '' : 'v-profile_photo_path-light-bg primary--text'"
                  :variant="!agent.profile_photo_path ? 'tonal' : undefined"
                >
                  <VImg
                    v-if="agent.profile_photo_path"
                    :src="agent.profile_photo_url"
                  />
                  <span v-else>{{ avatarText(agent.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
                </VAvatar>
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ agent.full_name }}</span>
                  <span class="text-sm text-medium-emphasis">
                    Customer ID: # {{ agent.id }}
                  </span>
                </div>
              </div>

              <div
                v-else
                class="d-flex align-center ms-4"
              >
                <VAvatar size="32">
                  <VImg :src="logo" />
                </VAvatar>
                <div class="d-flex flex-column ms-3">
                  <span class="d-block font-weight-medium text-truncate text-primary">
                    DT Traveler
                  </span>
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

            <div class="mx-sm-4">
              <CardTextPassportDetails :booking-traveler="bookingTraveler" />

              <CardTextContactDetails :booking-traveler="bookingTraveler" />

              <CardTextEmergencyContactDetails :booking-traveler="bookingTraveler" />

              <CardTextFoodAndDrugs :booking-traveler="bookingTraveler" />

              <CardTextHighlights :booking-traveler="bookingTraveler" />

              <FlightRow :booking-traveler="bookingTraveler" />
            </div>
          </VCard>

          <VDivider />

          <VCardText>
            <div class="d-flex mx-sm-4">
              <h6 class="text-base font-weight-medium me-1">
                All Rights Reserved by DT Travels (Pvt) Ltd. ©2022 | PV 00261435.
              </h6>
              <span>Thank You!</span>
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>

<style lang="scss">
.invoice-preview-table {
  --v-table-row-height: 44px !important;
}

@media print {
  .v-theme--dark {
    --v-theme-surface: 255, 255, 255;
    --v-theme-on-surface: 94, 86, 105;
  }

  body {
    background: none !important;
  }

  @page {
    margin: 0;
    size: auto;
  }

  .layout-page-content,
  .v-row,
  .v-col-md-9 {
    padding: 0;
    margin: 0;
  }

  .product-buy-now {
    display: none;
  }

  .v-navigation-drawer,
  .layout-vertical-nav,
  .app-customizer-toggler,
  .layout-footer,
  .layout-navbar,
  .layout-navbar-and-nav-container {
    display: none;
  }

  .v-card {
    box-shadow: none !important;

    .print-row {
      flex-direction: row !important;
    }
  }

  .layout-content-wrapper {
    padding-inline-start: 0 !important;
  }

  .v-table__wrapper {
    overflow: hidden !important;
  }
}
</style>
