<script setup>
import { layoutConfig } from '@layouts'
import { can } from '@layouts/plugins/casl'
import { useLayoutConfigStore } from '@layouts/stores/config'
import { Link } from "@inertiajs/vue3"
import {
  getComputedNavLinkToProp,
  getDynamicI18nProps,
  isNavLinkActive,
} from '@layouts/utils'

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})

// get url from browser

const fullUrl = window.location.href

// console.log(fullUrl)


const configStore = useLayoutConfigStore()
const hideTitleAndBadge = configStore.isVerticalNavMini()


</script>

<template>
  <li
    class="nav-link"
    :class="{ disabled: item.disable }"
  >
<!--    <Link-->
<!--      :class="activeClass"-->
<!--      :href="item.path ?? null"-->
<!--    >-->
<!--      <i-->
<!--        v-if="!item.icon"-->
<!--        class="v-icon  notranslate v-theme&#45;&#45;light nav-item-icon tabler-circle v-icon notranslate v-theme&#45;&#45;light nav-item-icon"-->
<!--        aria-hidden="true"-->
<!--        style="font-size: 10px; height: 10px; width: 10px;"-->
<!--      />-->
<!--      <Component-->
<!--        :is="layoutConfig.app.iconRenderer || 'div'"-->
<!--        v-else-->
<!--        v-bind="item.icon || layoutConfig.verticalNav.defaultNavItemIconProps"-->
<!--        class="nav-item-icon"-->
<!--      />-->
<!--      <span class="nav-item-title">{{ item.title }} </span>-->
<!--    </Link>-->

        <Component
          :is="item.to ? 'RouterLink' : 'a'"
          v-bind="getComputedNavLinkToProp(item)"
          :class="{ 'active-menu': isNavLinkActive(item, fullUrl) }"
        >
          <Component
            :is="layoutConfig.app.iconRenderer || 'div'"
            v-bind="item.icon || layoutConfig.verticalNav.defaultNavItemIconProps"
            class="nav-item-icon"
          />
          <TransitionGroup name="transition-slide-x">
            <!-- 👉 Title -->
            <Component
              :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
              v-show="!hideTitleAndBadge"
              key="title"
              class="nav-item-title"
              v-bind="getDynamicI18nProps(item.title, 'span')"
            >
              {{ item.title }}
            </Component>

            <!-- 👉 Badge -->
            <Component
              :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
              v-if="item.badgeContent"
              v-show="!hideTitleAndBadge"
              key="badge"
              class="nav-item-badge"
              :class="item.badgeClass"
              v-bind="getDynamicI18nProps(item.badgeContent, 'span')"
            >
              {{ item.badgeContent }}
            </Component>
          </TransitionGroup>
        </Component>
  </li>
</template>

<style lang="scss" scoped>
.layout-vertical-nav {
  .nav-link a {
    display: flex;
    align-items: center;
  }
}

.active-menu{
  background: linear-gradient(72.47deg,rgb(var(--v-theme-primary-800)) 22.16%,rgba(var(--v-theme-primary-700),.7) 76.47%)!important;
  box-shadow: 0 2px 6px rgba(var(--v-theme-primary-700),.48);
  font-weight: 500;
  color: snow !important;
}
</style>
