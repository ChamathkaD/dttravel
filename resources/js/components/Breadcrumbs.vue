<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const insertBetween = (items, insertion) => {
  return items.flatMap(
    (value, index, array) =>
      array.length - 1 !== index
        ? [value, insertion]
        : value,
  )
}

const breadcrumbs = computed(() => insertBetween(usePage().props.breadcrumbs || [], '/'))
</script>

<template>
  <nav
    v-if="breadcrumbs"
    class="mb-5"
  >
    <ul class="d-flex list-none">
      <li
        v-for="page in breadcrumbs"
        :key="page"
        class="mx-1"
      >
        <div>
          <span
            v-if="page ==='/'"
            class="text-lg"
          >/</span>
          <template v-else>
            <template v-if="page.current">
              <Link
                class="text-lg"
                :href="page.url"
                style="color: #000"
              >
                {{ page.title }}
              </Link>
            </template>
            <template v-else>
              <div
                class="text-lg text-disabled"
                style="color: #000"
              >
                {{ page.title }}
              </div>
            </template>
          </template>
        </div>
      </li>
    </ul>
  </nav>
</template>
