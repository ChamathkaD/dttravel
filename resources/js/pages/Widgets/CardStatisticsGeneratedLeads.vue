<script setup>
import { useTheme } from 'vuetify'
import { hexToRgb } from '@layouts/utils'

const props = defineProps({
  totalBookingCount: Number,
  totalInProgressBookingCount: Number,
  totalCancelBookingCount: Number,
  totalCompletedBookingCount: Number,
})

const vuetifyTheme = useTheme()

const series = [
  props.totalCancelBookingCount,
  props.totalInProgressBookingCount,
  props.totalCompletedBookingCount,
]

const chartOptions = computed(() => {
  const currentTheme = vuetifyTheme.current.value.colors
  const variableTheme = vuetifyTheme.current.value.variables
  const headingColor = `rgba(${hexToRgb(currentTheme['on-background'])},${variableTheme['high-emphasis-opacity']})`

  const chartColors = {
    donut: {
      series1: currentTheme.error,
      series2: currentTheme.warning,
      series3: currentTheme.success,
    },
  }

  return {
    chart: {
      parentHeightOffset: 0,
      type: 'donut',
    },
    labels: [
      'Cancel',
      'InProgress',
      'Completed',
    ],
    colors: [
      chartColors.donut.series1,
      chartColors.donut.series2,
      chartColors.donut.series3,
    ],
    stroke: { width: 0 },
    dataLabels: {
      enabled: false,
      formatter(val) {
        return `${Number.parseInt(val)}%`
      },
    },
    legend: { show: false },
    tooltip: { theme: false },
    grid: {
      padding: {
        top: 15,
        right: -20,
        left: -20,
      },
    },
    states: { hover: { filter: { type: 'none' } } },
    plotOptions: {
      pie: {
        donut: {
          size: '70%',
          labels: {
            show: true,
            value: {
              fontSize: '1.375rem',
              fontFamily: 'Public Sans',
              color: headingColor,
              fontWeight: 600,
              offsetY: -15,
              formatter(val) {
                return `${Number.parseInt(val)}%`
              },
            },
            name: {
              offsetY: 20,
              fontFamily: 'Public Sans',
            },
            total: {
              show: true,
              showAlways: true,
              color: currentTheme.success,
              fontSize: '.8125rem',
              label: 'Total',
              fontFamily: 'Public Sans',
              formatter() {
                return `${props.totalBookingCount}`
              },
            },
          },
        },
      },
    },
  }
})
</script>

<template>
  <VCard>
    <VCardText class="d-flex justify-space-between">
      <div class="d-flex flex-column">
        <div class="mb-auto">
          <h6 class="text-h5 text-no-wrap">
            Booking Summery
          </h6>
          <span class="text-sm">Report</span>
        </div>

        <div>
          <h5 class="text-h3 mb-1">
            {{ totalBookingCount }}
          </h5>
          <!--          <div>-->
          <!--            <VIcon-->
          <!--              icon="tabler-chevron-up"-->
          <!--              size="16"-->
          <!--              color="success"-->
          <!--              class="me-2"-->
          <!--            />-->
          <!--            <span class="text-success">0% </span>-->
          <!--          </div>-->
        </div>
      </div>
      <div>
        <VueApexCharts
          :options="chartOptions"
          :series="series"
          :height="147"
          :width="130"
        />
      </div>
    </VCardText>
  </VCard>
</template>
