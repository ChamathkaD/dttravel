<script setup>
import { Head } from "@inertiajs/vue3"
import TravelerActionHeader from "@/pages/Travelers/Partials/TravelerActionHeader.vue"
import TravelerBioPanel from "@/pages/Travelers/Partials/TravelerBioPanel.vue"
import TravelerOverview from "@/pages/Travelers/Partials/TravelerOverview.vue"
import TravelerRelationshipCard from "@/pages/Travelers/Partials/TravelerRelationshipCard.vue"
import PassportDetailsCard from "@/pages/Travelers/Partials/PassportDetailsCard.vue"
import ContactDetailsCard from "@/pages/Travelers/Partials/ContactDetailsCard.vue"
import EmergencyContactsCard from "@/pages/Travelers/Partials/EmergencyContactsCard.vue"
import FoodAndDrugStatusCard from "@/pages/Travelers/Partials/FoodAndDrugStatusCard.vue"
import HighlightDetailsCard from "@/pages/Travelers/Partials/HighlightDetailsCard.vue"
import TravelerBookingTable from "@/pages/Travelers/Partials/TravelerBookingTable.vue"

defineProps({
  traveler: Object,
  countries: Object,
  travelerAge: Number,
  travelerRelations: Object,
  travelers: Object,
  languages: Object,
})
</script>

<template>
  <Head :title="traveler.full_name" />

  <Breadcrumbs :traveler="traveler" />

  <TravelerActionHeader
    :traveler="traveler"
    :countries="countries"
    :travelers="travelers"
    :languages="languages"
  />

  <VRow>
    <VCol
      cols="12"
      md="5"
      lg="4"
    >
      <TravelerBioPanel
        :traveler="traveler"
        :traveler-age="travelerAge"
      />

      <TravelerRelationshipCard
        :traveler-relations="travelerRelations"
        class="mt-5"
      />
    </VCol>
    <VCol
      cols="12"
      md="7"
      lg="8"
    >
      <TravelerOverview />

      <PassportDetailsCard
        class="mt-5"
        :traveler="traveler"
      />

      <ContactDetailsCard
        class="mt-5"
        :traveler="traveler"
      />

      <EmergencyContactsCard
        class="mt-5"
        :traveler="traveler"
      />

      <FoodAndDrugStatusCard
        class="mt-5"
        :traveler="traveler"
      />

      <HighlightDetailsCard
        class="mt-5"
        :traveler="traveler"
      />
    </VCol>

    <VCol cols="12">
      <TravelerBookingTable />
    </VCol>
  </VRow>
</template>
