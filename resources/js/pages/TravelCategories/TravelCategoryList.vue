<script setup>
import { ref } from 'vue'
import { Head, Link, router } from "@inertiajs/vue3"
import CategoryCreateDialog from "@/pages/TravelCategories/Dialog/CategoryCreateDialog.vue"
import CategoryEditDialog from "@/pages/TravelCategories/Dialog/CategoryEditDialog.vue"
import { Confirm, Notify } from "notiflix"

const props = defineProps({
  categories: Object,
})

const search = ref('')
const isEditDialogVisible = ref(false)
const categoryData = ref({})

const handleEditDialog = item => {
  categoryData.value = item
  isEditDialogVisible.value = true
}

const headers = ref([
  { title: 'Category ID', key: 'id' },
  { title: 'Main Categories', key: 'parent' },
  { title: 'Sub Categories', key: 'child' },
  { title: 'Total Booking', key: 'total_booking' },
  { title: 'Actions', key: 'actions', sortable: false },
])

const handleCategoryDelete = categoryId => {
  if (route().params.trashed) {
    Confirm.show(
      'Delete Category Permanently?',
      'This category will be permanently deleted from the database. This cannot be undone.',
      'Delete',
      'No',
      () => {
        router.get(route("travel-categories.force-delete", categoryId), {}, {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("Category was successfully permanently deleted!")
          },
        })
      })
  } else {
    Confirm.show(
      'Category Delete',
      'Do you want to delete this category?',
      'Delete',
      'No',
      () => {
        router.delete(route("travel-categories.destroy", categoryId), {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("Category moved into trash!")
          },
        })
      })
  }
}

const handleRestore = categoryId => {
  Confirm.show(
    'Category Restore',
    'Would you like to restore this category record?',
    'Restore',
    'Cancel',
    () => {
      router.get(route("travel-categories.restore", categoryId), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("Category restored successfully!")
        },
      })
    })
}
</script>

<template>
  <Head title="Travel Categories" />
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
              placeholder="Search Category..."
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
            <CategoryCreateDialog :categories="categories" />

            <Link
              v-if="route().params.trashed"
              :href="route('travel-categories.index')"
            >
              <VBtn class="bg-primary-700">
                Categories
              </VBtn>
            </Link>

            <!--            <Link -->
            <!--              v-else -->
            <!--              :href="route('travel-categories.index', {'trashed': true})" -->
            <!--            > -->
            <!--              <VBtn -->
            <!--                class="bg-error" -->
            <!--                prepend-icon="tabler-trash" -->
            <!--              > -->
            <!--                Trashed Categories -->
            <!--              </VBtn> -->
            <!--            </Link> -->
          </VCol>
        </VRow>
      </VCardText>
      <VDataTable
        :headers="headers"
        :search="search"
        :items="categories"
        :items-per-page="10"
        class="text-no-wrap"
      >
        <!-- Category -->
        <template #item.parent="{ item }">
          <div class="d-flex align-center">
            <VAvatar
              size="32"
              :color="item.category_image_path ? '' : 'primary'"
              :class="item.category_image_path ? '' : 'v-avatar-light-bg primary--text'"
              :variant="!item.category_image_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="item.category_image_path"
                :src="item.category_image_url"
              />
              <VIcon
                v-else
                icon="tabler-category-2"
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
              class="d-flex align-center my-2 cursor-pointer"
              @click="handleEditDialog(descendant)"
            >
              <VAvatar
                size="32"
                :color="descendant.category_image_path ? '' : 'primary'"
                :class="descendant.category_image_path ? '' : 'v-avatar-light-bg primary--text'"
                :variant="!descendant.category_image_path ? 'tonal' : undefined"
              >
                <VImg
                  v-if="descendant.category_image_path"
                  :src="descendant.category_image_url"
                />
                <VIcon
                  v-else
                  icon="tabler-category-2"
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
          v-if="categories.length < 11"
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
            @click="handleCategoryDelete(item.id)"
          >
            <VIcon icon="tabler-trash" />
          </IconBtn>
        </template>
      </VDataTable>
    </VCard>
  </section>

  <!-- Category Edit Dialog -->
  <CategoryEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :category-data="categoryData"
    :categories="categories"
  />
</template>
