<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { Head } from "@inertiajs/vue3"

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

          <!-- 👉 Payment Details -->
          <VCardText
            v-if="agent"
            class="d-flex justify-space-between flex-wrap flex-column flex-sm-row print-row"
          >
            <div class="ma-sm-4">
              <h6 class="text-base font-weight-medium mb-1">
                Agent Details:
              </h6>
              <p class="mb-1">
                Agent ID:
                {{ agent.id }}
              </p>
              <p class="mb-1">
                Company Name:
                {{ agent.business_name }}
              </p>
              <p class="mb-1">
                Agent Name: {{ agent.full_name }}
              </p>
              <p class="mb-1">
                Mobile:
                {{ agent.contact }}
              </p>
              <p class="mb-0">
                Email:
                {{ agent.email }}
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

          <VTable class="invoice-preview-table border mx-10">
            <thead>
              <tr>
                <th scope="col">
                  Package
                </th>
                <th scope="col">
                  Type
                </th>
                <th scope="col">
                  COST
                </th>
                <th scope="col">
                  Pax
                </th>
                <th
                  scope="col"
                  class="text-end"
                >
                  Total
                </th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="item in travelPackage"
                :key="item.name"
              >
                <td class="text-no-wrap">
                  <div class="d-flex flex-column align-start">
                    <span class="text-body-1 font-weight-medium">
                      {{ item.packageName }}
                    </span>

                    <span class="text-sm text-disabled">
                      {{ item.subtitle }}
                    </span>
                  </div>
                </td>
                <td class="text-no-wrap">
                  <div class="d-flex flex-column align-start">
                    <span class="text-body-1 font-weight-medium">
                      Adults
                    </span>

                    <span class="text-sm text-disabled">
                      Child
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-start">
                    <span class="text-sm font-weight-medium">
                      {{ item.adultPrice }}
                    </span>

                    <span class="text-sm font-weight-medium">
                      {{ item.childPrice }}
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-start">
                    <span class="text-sm font-weight-medium">
                      {{ item.adultPax }}
                    </span>

                    <span class="text-sm font-weight-medium">
                      {{ item.childPax }}
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="float-right">
                    <div class="d-flex flex-column">
                      <span class="text-sm font-weight-bold">
                        {{ item.adultTotal }}
                      </span>

                      <span class="text-sm font-weight-bold">
                        {{ item.childTotal }}
                      </span>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </VTable>

          <!-- Total -->
          <VCardText class="d-flex justify-space-between flex-column flex-sm-row print-row">
            <div class="my-2 mx-sm-4 text-base">
              <div class="d-flex align-center mb-1">
                <h6 class="text-base font-weight-medium me-1">
                  Salesperson:
                </h6>
                <span />
              </div>
              <p>Thanks for your business</p>
            </div>

            <div class="my-2 mx-sm-4">
              <table>
                <tbody>
                  <tr>
                    <td class="text-end">
                      <div class="me-5">
                        <p class="mb-2">
                          Subtotal:
                        </p>
                        <p class="mb-2">
                          Discount:
                        </p>
                        <p class="mb-2">
                          Tax:
                        </p>
                        <p class="mb-2">
                          Total:
                        </p>
                      </div>
                    </td>

                    <td class="font-weight-medium text-high-emphasis">
                      <p class="mb-2">
                        $ {{ booking.amount }}
                      </p>
                      <p class="mb-2">
                        $ {{ booking.total_discount }}
                      </p>
                      <p class="mb-2">
                        $ {{ booking.total_tax }}
                      </p>
                      <p class="mb-2">
                        $ {{ booking.total_price }}
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </VCardText>

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
