<template>
  <q-page class="main-container">
    <div class="container">
      <div class="row justify-center">
        <div class="col-xl-12 text-center">
          <h1 className="h3-text page-header">
            {{ $t("faq.title") }}
          </h1>
        </div>
      </div>
      <div class="section">
        <div class="q-pa-md">
          <div class="row justify-center">
            <div class="col-md-9 .col-md-auto">
              <div class="q-pa-md">
                <q-list bordered class="rounded-borders" >
                  <q-expansion-item
                    v-for="item in faqs"
                    :key="item.id"
                    expand-separator
                    icon="help_outline"
                    color="primary"
                    :label="item.title"
                  >
                    <q-card>
                      <q-card-section v-html="item.answer">
                      </q-card-section>
                    </q-card>
                  </q-expansion-item>
                </q-list>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>
<script>
import { ref } from "vue";
import { api } from "boot/axios";
import { useMeta } from 'quasar'

const metaData = {
  title: 'Frequently Asked Questions',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: 'canonical',
      href: 'https://patent.istanbul/FAQs'
    }
  ]
}
export default {
  setup() {
    useMeta(metaData)
    return {
      faqs: ref([]),
    };
  },
  mounted: function () {

    var language = this.$i18n.locale;
    this.$nextTick(function () {
      api
        .get("/api/v1/faqs?language="+language)
        .then((res) => {
          this.faqs = res.data;
        })
        .catch((err) => {
        });
    });
  },
};
</script>
