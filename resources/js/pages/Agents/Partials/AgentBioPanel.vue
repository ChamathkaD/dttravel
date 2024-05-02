<script setup>
defineProps({
  agent: Object,
})
</script>

<template>
  <VRow v-if="agent">
    <VCol cols="12">
      <VCard class="d-sm-block d-md-flex justify-between align-center py-10">
        <VCardText class="text-center">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            :size="100"
            :color="!agent.profile_photo_path ? 'primary' : undefined"
            :variant="!agent.profile_photo_path ? 'tonal' : undefined"
          >
            <VImg
              v-if="agent.profile_photo_path"
              :src="agent.profile_photo_url"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(agent.full_name.split(' ').slice(0, 2).join(' ')) }}
            </span>
          </VAvatar>

          <!-- 👉 Customer fullName -->
          <h4 class="text-h4  mt-4">
            {{ agent.full_name }}
          </h4>
          <span class="text-sm">Customer ID #{{ agent.id }}</span>
        </VCardText>

        <!-- 👉 Agent Details -->
        <VCardText>
          <div class="text-disabled text-uppercase text-sm">
            Details
          </div>

          <VList class="card-list mt-2">
            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Email:</span>
                <span>
                  {{ agent.email }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Status:</span>
                <VChip
                  label
                  color="success"
                >
                  {{ agent.status }}
                </VChip>
              </div>
            </VListItem>

            <VListItem v-show="agent.contact">
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Contact:</span>
                <span>
                  {{ agent.contact }}
                </span>
              </div>
            </VListItem>

            <VListItem v-show="agent.address">
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Address:</span>
                <span>
                  {{ agent.address }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Country:</span>
                <span>
                  {{ agent.country }}
                </span>
              </div>
            </VListItem>
          </VList>
        </VCardText>

        <!-- Agent Owner Details -->
        <VCardText>
          <div class="text-disabled text-uppercase text-sm">
            Agent Owner
          </div>

          <VList class="card-list mt-2">
            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Name:</span>
                <span>
                  {{ agent.full_name }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Email:</span>
                <span>
                  {{ agent.email }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Contact:</span>
                <span>
                  {{ agent.contact }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Address:</span>
                <span>
                  {{ agent.address }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Country:</span>
                <span>
                  {{ agent.country }}
                </span>
              </div>
            </VListItem>

            <VListItem>
              <div class="text-body-1">
                <span class="font-weight-medium me-2">Language:</span>
                <span>
                  English
                </span>
              </div>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
