<script setup>
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  traveler: {
    type: Object,
    required: true,
  },
  travelerAge: Number,
})

const { hasRole } = usePermission()

const resolveUserStatusVariant = status => {
  const statLowerCase = status.toLowerCase()
  if (statLowerCase === 'pending')
    return 'warning'
  if (statLowerCase === 'active')
    return 'success'
  if (statLowerCase === 'inactive')
    return 'secondary'

  return 'primary'
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard v-if="traveler">
        <VCardText class="text-center pt-15">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            :size="100"
            :color="!traveler.profile_photo_path ? 'primary' : undefined"
            :variant="!traveler.profile_photo_path ? 'tonal' : undefined"
          >
            <VImg
              v-if="traveler.profile_photo_path"
              :src="traveler.profile_photo_url"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(traveler.full_name.split(' ').slice(0, 2).join(' ')) }}
            </span>
          </VAvatar>

          <!-- 👉 User fullName -->
          <h4 class="text-h4 mt-4">
            {{ traveler.full_name }} ({{ traveler.call_name }})
          </h4>
          <span class="text-sm">Traveler ID #{{ traveler.id }}</span>

          <div class="d-flex justify-center gap-x-5 mt-6">
            <div class="d-flex align-center">
              <VAvatar
                variant="tonal"
                color="primary"
                rounded
                class="me-3"
              >
                <VIcon icon="tabler-shopping-cart" />
              </VAvatar>
              <div class="d-flex flex-column align-start">
                <span class="text-body-1 font-weight-medium">194</span>
                <span class="text-body-2">Bookings</span>
              </div>
            </div>
            <div
              v-if="!hasRole('Staff Member')"
              class="d-flex align-center"
            >
              <VAvatar
                variant="tonal"
                color="primary"
                rounded
                class="me-3"
              >
                <VIcon icon="tabler-currency-dollar" />
              </VAvatar>
              <div class="d-flex flex-column align-start">
                <span class="text-body-1 font-weight-medium">$12,378</span>
                <span class="text-body-2">Spent</span>
              </div>
            </div>
          </div>
        </VCardText>

        <VDivider />

        <!-- 👉 Details -->
        <VCardText>
          <p class="text-sm text-uppercase text-disabled">
            Details
          </p>

          <!-- 👉 User Details list -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Status:

                  <VChip
                    label
                    size="small"
                    :color="resolveUserStatusVariant('success')"
                    class="text-capitalize"
                  >
                    {{ traveler?.status }}
                  </VChip>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Birthday:
                  <span class="text-body-1">{{ traveler?.dob }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Age:
                  <span class="text-body-1">{{ travelerAge }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Gender:
                  <span class="text-body-1">{{ traveler?.gender }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-h6">
                  Language:
                  <span class="text-body-1">{{ traveler?.language }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.75rem;
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
