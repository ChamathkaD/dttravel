<script setup>
import { ref } from 'vue'
import { Head, Link, router } from "@inertiajs/vue3"
import { Confirm, Notify } from "notiflix"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  travelPackages: Object,
})

const search = ref('')

const { hasRole } = usePermission()

const headers = ref([
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: 'Destination', key: 'destination' },
  { title: 'Category', key: 'category' },
  { title: 'Top Rated', key: 'top_rated' },
  { title: 'Actions', key: 'actions', sortable: false },
])

const handleTravelPackageDelete = travelPackageId => {
  if (route().params.trashed) {
    Confirm.show(
      'Delete Travel Package Permanently?',
      'This travel package will be permanently deleted from the database. This cannot be undone.',
      'Delete',
      'No',
      () => {
        router.get(route("travel-packages.force-delete", travelPackageId), {}, {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("Travel Package was successfully permanently deleted!")
          },
        })
      })
  } else {
    Confirm.show(
      'Travel Package Delete',
      'Do you want to delete this package?',
      'Delete',
      'No',
      () => {
        router.delete(route("travel-packages.destroy", travelPackageId), {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("Travel Package moved into trash!")
          },
        })
      })
  }
}

const handleRestore = travelPackageId => {
  Confirm.show(
    'Travel Package Restore',
    'Would you like to restore this travel package record?',
    'Restore',
    'Cancel',
    () => {
      router.get(route("travel-packages.restore", travelPackageId), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Travel Package restored successfully!")
        },
      })
    })
}
</script>

<template>
  <Head title="Travel Packages" />
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
              placeholder="Search Travel Package..."
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
            <Link
              v-if="!hasRole('Travel Agent')"
              :href="route('travel-packages.create')"
            >
              <VBtn
                class="bg-primary-700"
                prepend-icon="tabler-plus"
              >
                Add Travel Package
              </VBtn>
            </Link>

            <Link
              v-if="route().params.trashed"
              :href="route('travel-packages.index')"
            >
              <VBtn class="bg-primary-700">
                Travel Packages
              </VBtn>
            </Link>

            <!--            <Link -->
            <!--              v-else -->
            <!--              :href="route('travel-packages.index', {'trashed': true})" -->
            <!--            > -->
            <!--              <VBtn -->
            <!--                class="bg-error" -->
            <!--                prepend-icon="tabler-trash" -->
            <!--              > -->
            <!--                Trashed Travel Packages -->
            <!--              </VBtn> -->
            <!--            </Link> -->
          </VCol>
        </VRow>
      </VCardText>
      <VDataTable
        :headers="headers"
        :search="search"
        :items="travelPackages"
        :items-per-page="10"
        class="text-no-wrap"
      >
        <template #item.top_rated="{ item }">
          <VIcon
            v-if="item.top_rated"
            class="text-success"
            icon="tabler-square-rounded-check-filled"
          />
          <VIcon
            v-else
            class="text-warning"
            icon="tabler-square-rounded-x-filled"
          />
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn
            v-if="route().params.trashed"
            @click="handleRestore(item.id)"
          >
            <VIcon icon="tabler-restore" />
          </IconBtn>
          <Link :href="route('travel-packages.edit', item.id)">
            <IconBtn>
              <VIcon icon="tabler-edit" />
            </IconBtn>
          </Link>
          <IconBtn
            :class="route().params.trashed ? 'text-error' : ''"
            @click="handleTravelPackageDelete(item.id)"
          >
            <VIcon icon="tabler-trash" />
          </IconBtn>
        </template>
      </VDataTable>
    </VCard>
  </section>
</template>
