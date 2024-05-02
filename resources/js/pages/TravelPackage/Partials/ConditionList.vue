<script setup>
import { router } from "@inertiajs/vue3"

defineProps({
  conditions: Object,
})

const handleDeleteCondition = conditionId => {
  router.delete(route('travel-package-conditions.destroy', conditionId), {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <VTable
    class="mb-4 border rounded"
    density="compact"
  >
    <tbody>
      <tr
        v-for="condition in conditions"
        :key="condition.id"
      >
        <td>{{ condition.title }}</td>
        <td class="d-flex justify-end align-center">
          <IconBtn
            size="small"
            color="error"
            @click.prevent="handleDeleteCondition(condition.id)"
          >
            <VIcon
              icon="tabler-trash"
              size="small"
            />
          </IconBtn>
        </td>
      </tr>
    </tbody>
  </VTable>
</template>
