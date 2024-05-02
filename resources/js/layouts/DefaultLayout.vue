<script setup>
import navItems from '@/navigation/vertical'

// Components
import Footer from '@/layouts/components/Footer.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'

// @layouts plugin
import { VerticalNavLayout } from '@layouts'

// SECTION: Loading Indicator
const isFallbackStateActive = ref(false)
const refLoadingIndicator = ref(null)

watch([
  isFallbackStateActive,
  refLoadingIndicator,
], () => {
  if (isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.fallbackHandle()
  if (!isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.resolveHandle()
}, { immediate: true })
// !SECTION
</script>

<template>
  <div id="app">
    <VApp>
      <VerticalNavLayout :nav-items="navItems">
        <!-- 👉 navbar -->
        <template #navbar="{ toggleVerticalOverlayNavActive }">
          <div class="d-flex h-100 align-center">
            <div class="text-black">
              Have a good day!
            </div>
            <IconBtn
              id="vertical-nav-toggle-btn"
              class="ms-n3 d-lg-none"
              @click="toggleVerticalOverlayNavActive(true)"
            >
              <VIcon
                size="26"
                icon="tabler-menu-2"
              />
            </IconBtn>


            <VSpacer />


            <!--            <NavBarNotifications class="me-2" /> -->
            <UserProfile />
          </div>
        </template>

        <AppLoadingIndicator ref="refLoadingIndicator" />

        <div>
          <VContent>
            <slot />
          </VContent>
        </div>

        <!--    <RouterView v-slot="{ Component }"> -->
        <!--      <Suspense -->
        <!--        :timeout="0" -->
        <!--        @fallback="isFallbackStateActive = true" -->
        <!--        @resolve="isFallbackStateActive = false" -->
        <!--      > -->
        <!--        <Component :is="Component" /> -->
        <!--      </Suspense> -->
        <!--    </RouterView> -->

        <!-- 👉 Footer -->
        <template #footer>
          <Footer />
        </template>
      </VerticalNavLayout>
    </VApp>
  </div>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
