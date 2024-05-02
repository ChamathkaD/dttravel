<script setup>
import { router } from "@inertiajs/vue3"

defineProps({
  images: Object,
})

const handleDeleteTravelPackageImage = imageId => {
  router.delete(route('travel-package-images.destroy', imageId), {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <VRow>
    <VCol
      v-for="image in images"
      :key="image.id"
      class="d-flex child-flex"
      cols="4"
    >
      <VImg
        :src="image.travel_image_url"
        :lazy-src="image.travel_image_url"
        aspect-ratio="1"
        cover
        class="bg-grey-lighten-2"
      >
        <VBtn
          size="38"
          color="error"
          rounded
          class="ma-2"
          @click.prevent="handleDeleteTravelPackageImage(image.id)"
        >
          <VIcon
            icon="tabler-trash"
            size="22"
          />
        </VBtn>
        <template #placeholder>
          <VRow
            class="fill-height ma-0"
            align="center"
            justify="center"
          >
            <VProgressCircular
              indeterminate
              color="grey-lighten-5"
            />
          </VRow>
        </template>
      </VImg>
    </VCol>
  </VRow>
</template>
