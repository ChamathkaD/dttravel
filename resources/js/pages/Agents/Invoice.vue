<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { Head } from "@inertiajs/vue3"

const props = defineProps({
  agentPayment: Object,
  booking: Object,
  agent: Object,
  priceData: Array,
})

const resolveStatusVariant = status => {
  if (status === 'unpaid')
    return {
      color: 'error',
    }

  if (status === 'paid')
    return {
      color: 'success',
    }
}

const bookingData = [
  {
    id: props.booking.order_no,
    date: props.agentPayment.billing_date,
    adult: props.booking.price_per_adult,
    child: props.booking.price_per_child,
    adult_pax: props.booking.adult_count,
    child_pax: props.booking.child_count,
    b2b_rate: props.agent.agent_commission_rate,
    adult_total: props.priceData.adultTotal,
    child_total: props.priceData.childTotal,
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
                Booking #{{ booking.order_no }}
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
                  :color="resolveStatusVariant(agentPayment.status)?.color"
                  label
                >
                  {{ agentPayment.status.toUpperCase() }}
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
                  Booking ID
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
                <th scope="col">
                  B2B
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
                v-for="item in bookingData"
                :key="item.name"
              >
                <td class="text-no-wrap">
                  <div class="d-flex flex-column align-start">
                    <span class="text-body-1 font-weight-medium">
                      #{{ item.id }}
                    </span>

                    <span class="text-sm">
                      {{ item.date }}
                    </span>
                  </div>
                </td>
                <td class="text-no-wrap">
                  <div class="d-flex flex-column align-start">
                    <span class="text-sm">
                      Adults
                    </span>

                    <span class="text-sm">
                      Child
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-start">
                    <span class="text-sm font-weight-medium">
                      {{ item.adult }}
                    </span>

                    <span class="text-sm font-weight-medium">
                      {{ item.child }}
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-start">
                    <span class="text-sm font-weight-medium">
                      {{ item.adult_pax }}
                    </span>

                    <span class="text-sm font-weight-medium">
                      {{ item.child_pax }}
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-start">
                    <span class="text-sm font-weight-medium">
                      {{ item.b2b_rate }}%
                    </span>

                    <span class="text-sm font-weight-medium">
                      {{ item.b2b_rate }}%
                    </span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="float-right">
                    <div class="d-flex flex-column">
                      <span class="text-sm text-right font-weight-bold">
                        {{ item.adult_total.toFixed(2) }}
                      </span>

                      <span class="text-sm text-right font-weight-bold">
                        {{ item.child_total.toFixed(2) }}
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
                          Tax:
                        </p>
                        <p class="mb-2">
                          Total:
                        </p>
                      </div>
                    </td>

                    <td class="font-weight-medium text-high-emphasis">
                      <p class="mb-2 text-right">
                        $ {{ (parseFloat(priceData.adultTotal) + parseFloat(priceData.childTotal)).toFixed(2) }}
                      </p>
                      <p class="mb-2 text-right">
                        $ 0.00
                      </p>
                      <p class="mb-2 text-right">
                        $ {{ (parseFloat(priceData.adultTotal) + parseFloat(priceData.childTotal)).toFixed(2) }}
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
