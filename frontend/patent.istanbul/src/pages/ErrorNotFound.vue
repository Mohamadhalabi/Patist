<template>
  <div class="fullscreen main-container text-center q-pa-md flex flex-center">
    <div>
      <div style="font-size: 30vh">
        404
      </div>

      <div class="text-h2 text-dark" style="opacity:.4">
        {{ $t("error.nothing.here") }}
      </div>

      <q-btn
        class="q-mt-xl"
        color="dark"
        text-color="white"
        unelevated
        to="/"
        :label="[ $t('go.home')]"
        no-caps
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { useMeta } from 'quasar'

const metaData = {
  title: '404 Not Found',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: 'canonical',
      href: 'https://patent.istanbul/404'
    }
  ]
}


export default defineComponent({
  name: 'ErrorNotFound',
  setup() {
    useMeta(metaData)
  }
})
</script>
