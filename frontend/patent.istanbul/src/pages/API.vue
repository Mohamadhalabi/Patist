<template>
  <q-page class="main-container bg-light">
    <section class="intro-nine-Wrap">
      <div class="container">
        <div class="row">
          <div class="col-md-4 offset-md-1 col-sm-12 q-pa-md">
            <h1 class="text-h2">{{ $t("patist.api.integration") }}</h1>
            <p class="text-20 mb-4">
              {{ $t("if.you.want.to.integrate") }}
            </p>
            <div class="mb-5 lead">
              <ul class="list-inline">
                <li class="mb-3">{{ $t("instant.quote.app") }}</li>
                <li class="mb-3">{{ $t("quotes.fully.backed") }}</li>
                <li class="mb-3">{{ $t("easy.integration") }}</li>
              </ul>
            </div>
            <div>
              <button
                class="q-mx-md half-button btn-learn-more q-ma-md"
                @click="goto('services')"
                style="cursor: pointer"
              >
                {{ $t("learn.more") }}
              </button>
              <q-btn
                class="q-mx-md half-button btn-contact q-ma-md"
                :label="[$t('go.to.service')]"
                @click="contactModal = true"
              />
            </div>
          </div>
          <div class="col-md-4 col-sm-12 offset-md-1">
              <q-img loading="lazy" src="/images/api.png" mode="cover" />
          </div>
        </div>
      </div>
    </section>
    <div class="q-pa-md">
      <div class="row">
        <div class="col-md-8 offset-md-1 col-sm-12 q-pa-md q-mx-md" ref="services">
          <h3 class="h3" id="services">{{ $t("services") }}</h3>
          <p>
            {{ $t("we.prepare.our.patent.services") }}
          </p>
        </div>
      </div>
      <div class="row q-pa-md justify-center">
        <div class="col-md-3 col-xl-3 q-pa-md q-mx-md">
          <div class="card">
            <div class="card-title">
              <h6 class="q-my-md">{{ $t("patent.information.services") }}</h6>
            </div>
            <div class="card-body">
              <p>
                {{ $t("get.calculate.up.to.date") }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-xl-3 q-pa-md q-mx-md">
          <div class="card">
            <div class="card-title">
              <h6 class="q-my-md">{{ $t("patent.quoting.services") }}</h6>
            </div>
            <div class="card-body">
              <p>
                {{ $t("instantly.get.translation") }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-xl-3 q-pa-md q-mx-md">
          <div class="card">
            <div class="card-title">
              <h6 class="q-my-md">{{ $t("instructions") }}</h6>
            </div>
            <div class="card-body">
              <p>
                {{ $t("place.your.instructions") }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-xl-3 q-pa-md q-mx-md">
          <div class="card">
            <div class="card-title">
              <h6 class="q-my-md">{{ $t("batch.operations") }}</h6>
            </div>
            <div class="card-body">
              <p>
                {{ $t("instruct.multiple.renewals") }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-xl-3 q-pa-md q-mx-md">
          <div class="card">
            <div class="card-title">
              <h6 class="q-my-md">{{ $t("on.demand.info.services") }}</h6>
            </div>
            <div class="card-body">
              <p>
                {{ $t("get.up.to.date.bulletin.info") }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-xl-3 q-pa-md q-mx-md">
          <div class="card">
            <div class="card-title">
              <h6 class="q-my-md">{{ $t("callbacks") }}</h6>
            </div>
            <div class="card-body">
              <p>
                {{ $t("optionally.receive.the.important.operational.results") }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center">
          <q-btn
            class="q-mx-md half-button btn-contact q-ma-md"
            :label="[$t('go.to.service')]"
            @click="contactModal = true"
          />
        </div>
      </div>
    </div>
  </q-page>
  <q-dialog v-model="contactModal">
    <q-card>
      <ContactPage serviceSubject="API INTEGRATION" :msg="message"></ContactPage>
    </q-card>
  </q-dialog>
</template>

<script>
import { ref } from "vue";

import ContactPage from "pages/ContactPage";
import { useMeta } from "quasar";
const metaData = {
  title: "PATIST API Integration",
  titleTemplate: (title) => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: "canonical",
      href: "https://patent.istanbul/api",
    },
  ],
};
export default {
  components: { ContactPage },
  data() {
    return {
      message: false,
      margin: 20,
    };
  },
  methods: {
    goto(refName) {
      var element = this.$refs[refName];
      var top = element.offsetTop;
      window.scrollTo({
        top: top,
        behavior: 'smooth'
      });
    }
  },
  setup() {
    useMeta(metaData);
    return {
      contactModal: ref(false),
    };
  },
};
</script>

<style scoped>
.intro-nine-Wrap {
  color: #fff;
  background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);
}
.half-button {
  border: 1px solid;
  border-radius: 3px 15px!important;
  text-decoration: none;
  padding: 0.75rem 3rem;
  font-size: 16px;
}
.btn-learn-more {
  color: #fff;
  background-color: transparent;
  background-image: none;
  border-color: #fff;
}
.btn-contact {
  border-color: #ffc107;
  background-color: #ffc107;
  color: #000;
}
.bg-light {
  background: #fff !important;
}
.list-inline {
  list-style: none;
  padding-left: 0;
}
.list-inline li {
  margin-bottom: 1rem;
}
.text-20 {
  font-size: 20px;
}
.mb-4{
  margin-bottom: 1.5rem !important;
}
.card {
  border-radius: 10px;
  box-shadow: 0 1px 15px 1px rgb(0 0 0 / 4%), 0 1px 6px rgb(0 0 0 / 4%);
  border: 0;
  transition: all 0.15s ease-in;
  margin-bottom: 1.5rem !important;
  padding: 1.25rem;
}
</style>
