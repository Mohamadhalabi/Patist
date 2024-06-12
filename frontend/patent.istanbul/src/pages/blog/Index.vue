<template>
  <q-page class="main-container">
    <div class="container">
      <div class="section blog">
        <div class="row justify-center align-center service-title">
          <h1 class="text-h4 page-header">
            {{ $t("knowledge.base.title") }}
          </h1>
        </div>
        <div class="row">
          <div class="blog-container">
            <a
              :href="`${$t('knowledge-base-ep-validation')}`"
              class="knowledge-base-category"
            >
              <div class="col-12 col-sm">
                <div class="project">
                  <div class="text-center q-mt-md card-info">
                    <h6 class="text-h6">
                      {{ $t("ep.validation.in.tr") }}
                    </h6>
                  </div>
                  <q-img
                    src="/images/office-image.jpg"
                    alt="ep validation in türkiye"
                    class="project__img"
                  />
                </div>
              </div>
            </a>
            <a
              @click="show2"
              class="knowledge-base-category"
            >
              <div class="col-12 col-sm">
                <div class="project">
                  <div class="text-center q-mt-md card-info">
                    <h6 class="text-h6">
                      {{ $t("pct.entry.in.tr") }}
                    </h6>
                    <q-tooltip v-model="showing2">
                      Under Construction
                    </q-tooltip>
                  </div>
                  <img
                    src="https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1064&q=80"
                    alt="under construction"
                    class="project__img"
                  />
                </div>
              </div>
            </a>
            <a
              @click="show3"
              class="knowledge-base-category"
            >
              <div class="col-12 col-sm">
                <div class="project">
                  <div class="text-center q-mt-md card-info">
                    <h6 class="text-h6">
                      {{ $t("patent.application") }}
                    </h6>
                    <q-tooltip v-model="showing3">
                      Under Construction
                    </q-tooltip>
                  </div>
                  <img
                    src="https://images.unsplash.com/photo-1576153192396-180ecef2a715?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1974&q=80"
                    alt=" under construction"
                    class="project__img"
                  />
                </div>
              </div>
            </a>

            <a
              @click="show(showing)"
              class="knowledge-base-category"
            >
              <div class="">
                <div class="project">
                  <div class="text-center q-mt-md card-info">
                    <h6 class="text-h6">
                      {{ $t("further.topics") }}
                    </h6>
                    <q-tooltip v-model="showing">
                      Under Construction
                    </q-tooltip>
                  </div>
                  <img
                    src="https://images.unsplash.com/photo-1558655146-d09347e92766?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1064&q=80"
                    alt="further topics"
                    class="project__img"
                  />
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>
<script>
import { ref } from 'vue'
import { useMeta } from 'quasar'

const metaData = {
  title: 'Knowledge Base',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: 'canonical',
      href: 'https://patent.istanbul/about'
    }
  ],
}

export default {

  methods:{
    show(){
      this.showing = true;
      setTimeout(() => this.showing = false, 1000);
    },
    show2(){
      this.showing2 = true;
      setTimeout(() => this.showing2 = false, 1000);
    },
    show3(){
      this.showing3 = true;
      setTimeout(() => this.showing3 = false, 1000);
    }
  },
  setup () {
    useMeta(metaData)
    return {
      showing: ref(false),
      showing3: ref(false),
      showing2: ref(false)
    }
  }
}
</script>
