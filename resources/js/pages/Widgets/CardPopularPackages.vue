<script setup>
defineProps({
  popularPackages: Object,
})
</script>

<template>
  <VCard title="Popular Packages">
    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="popularPackage in popularPackages"
          :key="popularPackage.id"
        >
          <template #prepend>
            <VAvatar
              size="44"
              rounded
              color="secondary"
              icon="tabler-package"
              variant="tonal"
            />
          </template>

          <VListItemTitle class="font-weight-medium">
            <a
              :href="popularPackage.frontend_url"
              target="_blank"
              style="color: #3b405c"
            >
              {{ popularPackage.name }}
            </a>
          </VListItemTitle>
          <VListItemSubtitle class="text-disabled">
            {{ new Date(popularPackage.created_at).toDateString() }}
          </VListItemSubtitle>

          <template #append>
            <div class="d-flex align-center">
              <VIcon
                v-if="popularPackage.is_published"
                class="text-success"
                icon="tabler-square-rounded-check-filled"
              />
              <VIcon
                v-else
                class="text-warning"
                icon="tabler-square-rounded-x-filled"
              />
            </div>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 24px;
}
</style>
