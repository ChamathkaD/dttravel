<script setup>
import { Head, useForm } from "@inertiajs/vue3"
import CardStatistics from "@/pages/Widgets/CardStatistics.vue"
import CardStatisticsGeneratedLeads from "@/pages/Widgets/CardStatisticsGeneratedLeads.vue"
import CardPopularPackages from "@/pages/Widgets/CardPopularPackages.vue"
import CardRevenueReport from "@/pages/Widgets/CardRevenueReport.vue"
import { ref } from "vue"
import { Notify } from "notiflix"
import NoData from '@images/svg/no_data.svg'

const props = defineProps({
  bookingCount: Number,
  travelersCount: Number,
  popularPackages: Object,
  totalSales: String,
  totalRevenue: String,
  salesData: Array,
  revenueData: Array,
  auth: Object,
  totalBookingCount: Number,
  totalInProgressBookingCount: Number,
  totalCancelBookingCount: Number,
  totalCompletedBookingCount: Number,
})

const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const isDialogVisible = ref(props.auth.user.isMustChangePassword)
const passwordInput = ref(null)

const form = useForm({
  password: '',
  password_confirmation: '',
})

const handleChangePassword = () => {
  form.put(route('user-first-login-password.update'), {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      Notify.success('Your password has been updated!')
      isDialogVisible.value = false
    },
    onError: () => {
      if (form.errors.password) {
        passwordInput.value.focus()
      }
    },
  })
}
</script>

<template>
  <Head title="Dashboard"/>

  <VRow class="match-height">
    <VCol
      cols="12"
      md="7"
      lg="8"
    >
      <CardStatistics
        :booking-count="bookingCount"
        :travelers-count="travelersCount"
        :total-sales="totalSales"
        :total-revenue="totalRevenue"
        class="h-100"
      />
    </VCol>

    <VCol
      cols="12"
      md="5"
      lg="4"
    >
      <CardStatisticsGeneratedLeads
        :total-booking-count="totalBookingCount"
        :total-in-progress-booking-count="totalInProgressBookingCount"
        :total-cancel-booking-count="totalCancelBookingCount"
        :total-completed-booking-count="totalCompletedBookingCount"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <CardRevenueReport
        v-if="salesData.length !== 0 || revenueData.length !== 0"
        :revenue-data="revenueData"
        :sales-data="salesData"
      />

      <div
        v-else
        class="d-flex align-center h-100"
      >
        <div class="d-flex flex-column align-center w-100 justify-center">
          <img
            :src="NoData"
            alt="No-Data"
            class="w-50"
          >
          <p class="mt-3">
            No Sales & Revenue Data
          </p>
        </div>
      </div>
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <CardPopularPackages
        v-if="popularPackages.length !== 0"
        :popular-packages="popularPackages"
      />
    </VCol>
  </VRow>

  <VDialog
    v-model="isDialogVisible"
    persistent
    class="v-dialog-sm"
  >
    <!-- Dialog Content -->
    <VCard title="Change Password">
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppTextField
              id="password"
              ref="passwordInput"
              v-model="form.password"
              label="Password"
              placeholder="············"
              :type="isPasswordVisible ? 'text' : 'password'"
              :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
              :error-messages="form.errors.password"
              @click:append-inner="isPasswordVisible = !isPasswordVisible"
            />
          </VCol>

          <VCol cols="12">
            <AppTextField
              id="password_confirmation"
              v-model="form.password_confirmation"
              label="Confirm Password"
              placeholder="············"
              :type="isConfirmPasswordVisible ? 'text' : 'password'"
              :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
              @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
            />
          </VCol>
        </VRow>
      </VCardText>

      <VCardText class="d-flex justify-end gap-3 flex-wrap">
        <VBtn @click="handleChangePassword">
          Change Password
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>

<style lang="scss">
@use "@core-scss/template/libs/apex-chart.scss";
</style>
