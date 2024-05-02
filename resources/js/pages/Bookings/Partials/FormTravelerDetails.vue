<script setup>
import { useForm } from "@inertiajs/vue3"
import { Notify } from "notiflix"

const props = defineProps({
  booking: Object,
  travelers: Object,
  agent: Object,
})

const form = useForm({
  traveler_id: null,
  flight_number: null,
  flight_date: null,
  dispatcher_time: null,
  dispatcher_airport: null,
  arrival_time: null,
  arrival_airport: null,
})

const filteredTravelers = computed(() => {
  if (props.agent) {
    return props.travelers.filter(traveler => traveler.agent_id === props.agent.id)
  } else {
    return props.travelers
  }
})

const handleUpdateTraveler = () => {
  form.post(route("bookings.add-traveler-for-booking", props.booking.id), {
    preserveScroll: true,
    onFinish: () => form.reset(),
    onSuccess: () => {
      Notify.success("Traveler Added!")
      form.reset()
    },
  })
}
</script>

<template>
  <VCard
    :title="`Add ${booking.travelers ? booking.travelers.length + 1 : 1} Traveler Details`"
    class="mb-6 mt-6"
    v-if="booking.status === 'InProgress'"
  >

    <VCardText>
      <VRow>
        <VCol>
          <AppSelect
            v-model="form.traveler_id"
            :error-messages="form.errors.traveler_id"
            :items="filteredTravelers"
            item-title="full_name"
            item-value="id"
            placeholder="Select Traveler"
            label="Search Traveler *"
            clearable
            clear-icon="tabler-x"
          />
        </VCol>
      </VRow>

      <VRow>
        <VCol>
          <div class="text-high-emphasis font-weight-medium">
            Traveler Flight Details
          </div>
        </VCol>
      </VRow>

      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="form.flight_number"
            label="Flight Number *"
            :error-messages="form.errors.flight_number"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="form.flight_date"
            label="Flight Date *"
            type="date"
            :error-messages="form.errors.flight_date"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="form.dispatcher_time"
            label="Departure Time *"
            type="time"
            :error-messages="form.errors.dispatcher_time"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="form.dispatcher_airport"
            label="Departure Airport *"
            :error-messages="form.errors.dispatcher_airport"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="form.arrival_time"
            label="Arrival Time *"
            type="time"
            :error-messages="form.errors.arrival_time"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <AppTextField
            v-model="form.arrival_airport"
            label="Arrival Airport *"
            :error-messages="form.errors.arrival_airport"
          />
        </VCol>
      </VRow>

      <VRow>
        <VCol>
          <VBtn
            color="primary"
            variant="elevated"
            block
            @click.prevent="handleUpdateTraveler"
          >
            Add
            {{ booking.travelers ? booking.travelers.length + 1 : 1 }}
            Traveler & Flight Details
          </VBtn>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
