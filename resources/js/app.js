import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import '@core-scss/template/index.scss'
import '@styles/styles.scss'

import { registerPlugins } from '@core/utils/plugins'

import DefaultLayout from "@/layouts/DefaultLayout.vue"
import { themeConfig } from "@themeConfig"
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'

createInertiaApp({
  title: title => `${title} - ${themeConfig.app.title}`,
  resolve: name => {
    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
    let page = pages[`./pages/${name}.vue`]
    page.default.layout = page.default.layout || DefaultLayout
    
    return page
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)


    registerPlugins(app)

    app.mount(el)
  },
})
