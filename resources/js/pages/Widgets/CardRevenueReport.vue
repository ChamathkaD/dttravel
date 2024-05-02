<script setup>
import { useTheme } from 'vuetify'
import { hexToRgb } from '@layouts/utils'

const props = defineProps({
  salesData: Array,
  revenueData: Array,
})

const vuetifyTheme = useTheme()

const series = [
  {
    name: 'Sales',
    type: 'column',
    data: Object.values(props.salesData),
  },
  {
    name: 'Revenue',
    type: 'column',
    data: Object.values(props.salesData),
  },
]

const chartOptions = computed(() => {
  const currentTheme = vuetifyTheme.current.value.colors
  const variableTheme = vuetifyTheme.current.value.variables
  const labelColor = `rgba(${hexToRgb(currentTheme['on-surface'])},${variableTheme['disabled-opacity']})`
  const legendColor = `rgba(${hexToRgb(currentTheme['on-background'])},${variableTheme['high-emphasis-opacity']})`

  return {
    chart: {
      stacked: false,
      type: 'bar',
      toolbar: { show: true },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "$ " + (Math.round(val * 100) / 100).toFixed(2)
        },
      },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '40%',
        borderRadius: 8,
        startingShape: 'rounded',
        endingShape: 'rounded',
      },
    },
    colors: [
      currentTheme.primary,
      currentTheme.warning,
    ],
    dataLabels: { enabled: false },
    stroke: {
      curve: 'smooth',
      width: [1, 1],
      lineCap: 'round',
      colors: [currentTheme.surface],
    },
    legend: {
      show: true,
      horizontalAlign: 'left',
      position: 'top',
      fontFamily: 'Public Sans',
      fontSize: '13px',
      markers: {
        height: 12,
        width: 12,
        radius: 12,
        offsetX: -3,
        offsetY: 2,
      },
      labels: { colors: legendColor },
      itemMargin: { horizontal: 5 },
    },
    grid: {
      show: false,
      padding: {
        bottom: -8,
        top: 20,
      },
    },
    xaxis: {
      categories: Object.keys(props.salesData),
      labels: {
        style: {
          fontSize: '13px',
          colors: labelColor,
          fontFamily: 'Public Sans',
        },
      },
      axisTicks: { show: false },
      axisBorder: { show: false },
    },
    yaxis: {
      labels: {
        offsetX: -16,
        style: {
          fontSize: '13px',
          colors: labelColor,
          fontFamily: 'Public Sans',
        },
      },
      tickAmount: 5,
    },
    responsive: [
      {
        breakpoint: 1700,
        options: { plotOptions: { bar: { columnWidth: '43%' } } },
      },
      {
        breakpoint: 1441,
        options: { plotOptions: { bar: { columnWidth: '52%' } } },
      },
      {
        breakpoint: 1280,
        options: { plotOptions: { bar: { columnWidth: '38%' } } },
      },
      {
        breakpoint: 1025,
        options: {
          plotOptions: { bar: { columnWidth: '70%' } },
          chart: { height: 390 },
        },
      },
      {
        breakpoint: 991,
        options: { plotOptions: { bar: { columnWidth: '38%' } } },
      },
      {
        breakpoint: 850,
        options: { plotOptions: { bar: { columnWidth: '48%' } } },
      },
      {
        breakpoint: 449,
        options: {
          plotOptions: { bar: { columnWidth: '70%' } },
          chart: { height: 360 },
          xaxis: { labels: { offsetY: -5 } },
        },
      },
      {
        breakpoint: 394,
        options: { plotOptions: { bar: { columnWidth: '88%' } } },
      },
    ],
    states: {
      hover: { filter: { type: 'none' } },
      active: { filter: { type: 'none' } },
    },
  }
})
</script>

<template>
  <VCard>
    <VRow no-gutters>
      <VCol cols="12">
        <VCardText class="pe-2">
          <h6 class="text-h5 mb-6">
            Revenue Report
          </h6>

          <VueApexCharts
            :options="chartOptions"
            :series="series"
            height="365"
          />
        </VCardText>
      </VCol>
    </VRow>
  </VCard>
</template>
