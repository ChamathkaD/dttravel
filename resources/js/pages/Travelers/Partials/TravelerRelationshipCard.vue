<script setup>
defineProps({
  travelerRelations: Object,
})
</script>

<template>
  <VCard v-if="travelerRelations.length !== 0">
    <VCardItem title="Relation Travelers" />
    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="relation in travelerRelations"
          :key="relation.id"
        >
          <template #prepend>
            <VAvatar
              size="34"
              :color="relation.traveler.profile_photo_path ? '' : 'primary'"
              :class="relation.traveler.profile_photo_path ? '' : 'v-avatar-light-bg primary--text'"
              :variant="relation.traveler.profile_photo_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="relation.traveler.profile_photo_path !== null"
                :src="relation.traveler.profile_photo_url"
              />
              <span v-else>{{ avatarText(relation.traveler.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
            </VAvatar>
          </template>
          <VListItemTitle class="font-weight-medium">
            {{ relation.traveler.full_name }}
          </VListItemTitle>
          <VListItemSubtitle class="text-disabled">
            {{ relation.relation_type }}
          </VListItemSubtitle>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>
