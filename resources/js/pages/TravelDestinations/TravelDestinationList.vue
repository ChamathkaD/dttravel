<script setup>
import { ref } from 'vue'
import { Head, Link, router } from "@inertiajs/vue3"
import { Confirm, Notify } from "notiflix"
import DestinationEditDialog from "@/pages/TravelDestinations/Dialog/DestinationEditDialog.vue"
import DestinationCreateDialog from "@/pages/TravelDestinations/Dialog/DestinationCreateDialog.vue"

const props = defineProps({
  destinations: Object,
})

const search = ref('')
const isEditDialogVisible = ref(false)
const destinationData = ref({})

const handleEditDialog = item => {
  destinationData.value = item
  isEditDialogVisible.value = true
}

const headers = ref([
  { title: 'Destination ID', key: 'id' },
  { title: 'Main Destination', key: 'parent' },
  { title: 'Sub Destination', key: 'child' },
  { title: 'Total Booking', key: 'total_booking' },
  { title: 'Actions', key: 'actions', sortable: false },
])

const handleDestinationDelete = destinationId => {
  if (route().params.trashed) {
    Confirm.show(
      'Delete Destination Permanently?',
      'This destination will be permanently deleted from the database. This cannot be undone.',
      'Delete',
      'No',
      () => {
        router.get(route("travel-destinations.force-delete", destinationId), {}, {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("Destination was successfully permanently deleted!")
          },
        })
      })
  } else {
    Confirm.show(
      'Destination Delete',
      'Do you want to delete this destination?',
      'Delete',
      'No',
      () => {
        router.delete(route("travel-destinations.destroy", destinationId), {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("Destination  moved into trash!")
          },
        })
      })
  }
}

const handleRestore = destinationId => {
  Confirm.show(
    'Destination Restore',
    'Would you like to restore this destination record?',
    'Restore',
    'Cancel',
    () => {
      router.get(route("travel-destinations.restore", destinationId), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Destination restored successfully!")
        },
      })
    })
}
</script>

<template>
  <Head title="Travel Destination" />
  <Breadcrumbs />

  <section>
    <VCard>
      <VCardText>
        <VRow class="d-flex justify-space-between">
          <VCol
            cols="12"
            md="3"
            sm="6"
          >
            <AppTextField
              v-model="search"
              density="compact"
              placeholder="Search Destination..."
              append-inner-icon="tabler-search"
              single-line
              hide-details
              dense
              outlined
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            class="d-flex gap-3 justify-end"
          >
            <DestinationCreateDialog :destinations="destinations" />

            <Link
              v-if="route().params.trashed"
              :href="route('travel-destinations.index')"
            >
              <VBtn class="bg-primary-700">
                Destinations
              </VBtn>
            </Link>

            <!--            <Link -->
            <!--              v-else -->
            <!--              :href="route('travel-destinations.index', {'trashed': true})" -->
            <!--            > -->
            <!--              <VBtn -->
            <!--                class="bg-error" -->
            <!--                prepend-icon="tabler-trash" -->
            <!--              > -->
            <!--                Trashed Destinations -->
            <!--              </VBtn> -->
            <!--            </Link> -->
          </VCol>
        </VRow>
      </VCardText>
      <VDataTable
        :headers="headers"
        :search="search"
        :items="destinations"
        :items-per-page="10"
        class="text-no-wrap"
      >
        <!-- Destination -->
        <template #item.parent="{ item }">
          <div class="d-flex align-center">
            <VAvatar
              size="32"
              :color="item.destination_image_path ? '' : 'primary'"
              :class="item.destination_image_path ? '' : 'v-avatar-light-bg primary--text'"
              :variant="!item.destination_image_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="item.destination_image_path"
                :src="item.destination_image_url"
              />
              <VIcon
                v-else
                icon="tabler-directions"
              />
            </VAvatar>
            <div class="d-flex flex-column ms-3">
              <span class="d-block font-weight-medium text-high-emphasis text-truncate">
                {{ item.name }}
              </span>
            </div>
          </div>
        </template>

        <template #item.child="{ item }">
          <template
            v-for="descendant in item.descendants"
            :key="descendant.id"
          >
            <div
              class="d-flex align-center my-2"
              @click="handleEditDialog(descendant)"
            >
              <VAvatar
                size="32"
                :color="descendant.destination_image_path ? '' : 'primary'"
                :class="descendant.destination_image_path ? '' : 'v-avatar-light-bg primary--text'"
                :variant="!descendant.destination_image_path ? 'tonal' : undefined"
              >
                <VImg
                  v-if="descendant.destination_image_path"
                  :src="descendant.destination_image_url"
                />
                <VIcon
                  v-else
                  icon="tabler-directions"
                />
              </VAvatar>
              <div class="d-flex flex-column ms-3">
                <span class="d-block font-weight-medium text-high-emphasis text-truncate">
                  {{ descendant.name }}
                  <VIcon
                    size="small"
                    icon="tabler-edit"
                  />
                </span>
              </div>
            </div>
          </template>
        </template>

        <template
          v-if="destinations.length < 11"
          #bottom
        />

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn
            v-if="route().params.trashed"
            @click="handleRestore(item.id)"
          >
            <VIcon icon="tabler-restore" />
          </IconBtn>
          <IconBtn @click="handleEditDialog(item)">
            <VIcon icon="tabler-edit" />
          </IconBtn>
          <IconBtn
            :class="route().params.trashed ? 'text-error' : ''"
            @click="handleDestinationDelete(item.id)"
          >
            <VIcon icon="tabler-trash" />
          </IconBtn>
        </template>
      </VDataTable>
    </VCard>
  </section>

  <!-- Destination Edit Dialog -->
  <DestinationEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :destination-data="destinationData"
    :destinations="destinations"
  />
</template>
