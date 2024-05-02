<script setup>
const props = defineProps({
  booking: Object,
})

const headers = [
  {
    title: 'Package Name',
    key: 'package',
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
    title: 'Total',
    key: 'total',
    align: 'end',
  },
]

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
</script>

<template>
  <VCard class="mb-6">
    <VCardItem>
      <template #title>
        <h5 class="text-h5">
          Booking Details
        </h5>
        <!--      </template> -->
        <!--      <template #append> -->
        <!--        <VBtn variant="text"> -->
        <!--          Edit or Add Extras -->
        <!--        </VBtn> -->
        <!--      </template> -->
      </template>
    </VCardItem>

    <VDivider />
    <VDataTable
      :headers="headers"
      :items="travelPackage"
      item-value="packageName"
      show-select
      class="text-no-wrap"
    >
      <template #item.package="{ item }">
        <div class="d-flex gap-x-3">
          <div class="d-flex flex-column align-start">
            <span class="text-body-1 font-weight-medium">
              {{ item.packageName }}
            </span>

            <span class="text-sm text-disabled">
              {{ item.subtitle }}
            </span>
          </div>
        </div>
      </template>

      <template #item.price="{ item }">
        <div class="d-flex flex-column align-start">
          <span class="text-sm font-weight-medium">
            {{ item.adultPrice }}
          </span>

          <span class="text-sm font-weight-medium">
            {{ item.childPrice }}
          </span>
        </div>
      </template>

      <template #item.pax="{ item }">
        <div class="d-flex flex-column align-start">
          <span class="text-sm font-weight-medium">
            {{ item.adultPax }}
          </span>

          <span class="text-sm font-weight-medium">
            {{ item.childPax }}
          </span>
        </div>
      </template>

      <template #item.total="{ item }">
        <div class="float-right">
          <div class="d-flex flex-column">
            <span class="text-sm text-right font-weight-bold">
              {{ item.adultTotal }}
            </span>

            <span class="text-sm text-right font-weight-bold">
              {{ item.childTotal }}
            </span>
          </div>
        </div>
      </template>

      <template #bottom />
    </VDataTable>
    <VDivider />

    <VCardText>
      <div class="d-flex align-end flex-column">
        <table class="text-high-emphasis">
          <tbody>
            <tr>
              <td width="200px">
                Subtotal:
              </td>
              <td class="text-right">
                $ {{ booking.amount }}
              </td>
            </tr>
            <tr class="text-success">
              <td>Discount:</td>
              <td class="text-right">
                $ {{ booking.total_discount.toFixed(2) }}
              </td>
            </tr>
            <tr>
              <td>Tax:</td>
              <td class="text-right">
                $ {{ booking.total_tax }}
              </td>
            </tr>
            <tr>
              <td class="text-high-emphasis font-weight-medium">
                Total:
              </td>
              <td class="font-weight-medium text-right">
                $ {{ booking.total_price }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </VCardText>
  </VCard>
</template>
