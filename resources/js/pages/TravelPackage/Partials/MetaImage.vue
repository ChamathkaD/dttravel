<script setup>
import { router } from "@inertiajs/vue3"

const props = defineProps({
  travelPackage: Object,
})

const handleDeleteMetaImage = () => {
  router.put(route('travel-package.delete-meta-image', props.travelPackage.id), {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="pb-6 d-flex align-self-center">
    <VImg
      :src="travelPackage.meta_image_url"
      class="cursor-pointer rounded"
      width="200"
      height="auto"
    >
      <VBtn
        size="38"
        color="error"
        rounded
        class="ma-2"
        @click.prevent="handleDeleteMetaImage"
      >
        <VIcon
          icon="tabler-trash"
          size="22"
        />
      </VBtn>
    </VImg>
  </div>
</template>
