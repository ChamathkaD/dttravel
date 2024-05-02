<script setup>
import { formatDate } from "../../../@core/utils/formatters"

defineProps({
  bookingNotes: Object,
})
</script>

<template>
  <VCard title="Travel Note">
    <VCardText
      v-for="(bookingNote, index) in bookingNotes"
      :key="index"
    >
      <div class="d-flex justify-space-between mb-2">
        <span style="color:#000;">{{ bookingNote.title }}</span>
        <span class="text-sm text-disabled">{{ formatDate(bookingNote.created_at) }}</span>
      </div>
      {{ bookingNote.note }}
    </VCardText>
  </VCard>
</template>
