<script setup>
const props = defineProps({
  booking: Object,
  agent: Object,
  priceData: Array,
  agentPayment: Object,
})

const headers = [
  {
    title: 'Booking ID',
    key: 'id',
  },
  {
    title: 'Price',
    key: 'price',
  },
  {
    title: 'Pax',
    key: 'pax',
  },
  {
    title: 'B2B Rate',
    key: 'b2b_rate',
  },
  {
    title: 'Total',
    key: 'total',
    align: 'end',
  },
]

const bookingData = [
  {
    id: props.booking.order_no,
    date: props.agentPayment.billing_date,
    adult: props.booking.price_per_adult,
    child: props.booking.price_per_child,
    total: props.booking.total_price,
    adult_pax: props.booking.adult_count,
    child_pax: props.booking.child_count,
    b2b_rate: props.agent.agent_commission_rate,
    adult_total: props.priceData.adultTotal,
    child_total: props.priceData.childTotal,
  },
]
</script>

<template>
  <VRow>
    <VCol cols="12">
      <!-- 👉 Order Details -->
      <VCard class="mb-6">
        <VCardItem>
          <template #title>
            <h5 class="text-h5">
              Booking Details
            </h5>
          </template>
        </VCardItem>

        <VDivider/>
        <VDataTable
          :headers="headers"
          :items="bookingData"
          class="text-no-wrap"
        >
          <template #item.id="{ item }">
            <div class="d-flex gap-x-3">
              <div class="d-flex flex-column align-start">
                <span class="text-body-1 font-weight-medium">
                  #{{ item.id }}
                </span>

                <span class="text-sm text-disabled">
                  {{ item.date }}
                </span>
              </div>
            </div>
          </template>

          <template #item.price="{ item }">
            <div
              v-if="item.adult == 0.00 && item.child == 0.00"
              class="d-flex flex-column align-start"
            >
              <span class="text-sm font-weight-medium">
                {{ item.total }}
              </span>
            </div>
            <div
              v-else
              class="d-flex flex-column align-start"
            >
              <span class="text-sm font-weight-medium">
                {{ item.adult }}
              </span>

              <span class="text-sm font-weight-medium">
                {{ item.child }}
              </span>
            </div>
          </template>

          <template #item.pax="{ item }">
            <div class="d-flex flex-column align-start">
              <span class="text-sm font-weight-medium">
                {{ item.adult_pax }}
              </span>

              <span class="text-sm font-weight-medium">
                {{ item.child_pax }}
              </span>
            </div>
          </template>

          <template #item.b2b_rate="{ item }">
            <div
              v-if="item.adult == 0.00 && item.child == 0.00"
              class="d-flex flex-column align-start"
            >
              <span class="text-sm font-weight-medium">
                {{ item.b2b_rate }}%
              </span>
            </div>

            <div
              v-else
              class="d-flex flex-column align-start"
            >
              <span class="text-sm font-weight-medium">
                {{ item.b2b_rate }}%
              </span>

              <span class="text-sm font-weight-medium">
                {{ item.b2b_rate }}%
              </span>
            </div>
          </template>

          <template #item.total="{ item }">
            <div
              v-if="item.adult == 0.00 && item.child == 0.00"
              class="d-flex flex-column align-end"
            >
              <span class="text-sm font-weight-medium">
                {{ (parseFloat(item.total.replace(",", "")) * item.b2b_rate / 100).toFixed(2) }}
              </span>
            </div>

            <div
              v-else
              class="d-flex flex-column align-end"
            >
              <span class="text-sm font-weight-medium">
                {{ item.adult_total.toFixed(2) }}
              </span>

              <span class="text-sm font-weight-medium">
                {{ item.child_total.toFixed(2) }}
              </span>
            </div>
          </template>

          <template #bottom/>
        </VDataTable>
        <VDivider/>

        <VCardText>
          <div class="d-flex align-end flex-column">
            <table class="text-high-emphasis">
              <tbody>
              <tr>
                <td width="200px">
                  Subtotal:
                </td>
                <td v-if="booking.price_per_child === '0.00' && booking.price_per_adult === '0.00'">
                  {{ (parseFloat(bookingData[0].total.replace(",", "")) * bookingData[0].b2b_rate / 100).toFixed(2) }}
                </td>
                <td v-else>
                  {{ (parseFloat(priceData.adultTotal) + parseFloat(priceData.childTotal)).toFixed(2) }}
                </td>
              </tr>
              <tr>
                <td class="text-high-emphasis font-weight-medium">
                  Total:
                </td>
                <td
                  v-if="booking.price_per_child === '0.00' && booking.price_per_adult === '0.00'"
                  class="font-weight-medium"
                >
                  {{ (parseFloat(bookingData[0].total.replace(",", "")) * bookingData[0].b2b_rate / 100).toFixed(2) }}
                </td>
                <td
                  v-else
                  class="font-weight-medium"
                >
                  {{ (parseFloat(priceData.adultTotal) + parseFloat(priceData.childTotal)).toFixed(2) }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
