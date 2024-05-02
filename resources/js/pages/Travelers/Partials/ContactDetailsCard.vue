<script setup>
const props = defineProps({
  traveler: Object,
})
</script>

<template>
  <VCard
    v-if="traveler"
    prepend-icon="tabler-phone text-secondary"
    title="Contact Details"
  >
    <VDivider />
    <VCardText>
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VList>
            <VListItem
              title="Mobile Number"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.contact }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Email"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.email }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Address"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.address }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Country"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.country }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="State"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.state }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Zip Code"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.zip }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VList>
            <VListItem
              title="Whatsapp"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.whatsapp }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Instagram Username"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.instagram }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Facebook Username"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.facebook }}
              </VListItemSubtitle>
            </VListItem>

            <VListItem
              title="Tiktok Username"
              class="text-disabled"
            >
              <VListItemSubtitle
                style="color: black"
                class="text-md-body-1"
              >
                {{ traveler.tiktok }}
              </VListItemSubtitle>
            </VListItem>
          </VList>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
