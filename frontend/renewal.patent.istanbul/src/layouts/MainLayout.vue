<template>
  <q-layout ref="layout" view="hHh Lpr lff">
    <q-header elevated class="bg-white text-grey-8">
      <q-toolbar class="GNL__toolbar">
        <q-toolbar-title shrink style="margin-top:15px">
            <a href="/">
              <img
                v-bind="logoImageProps"
                width="180"
                height="86"
                src="/images/patist-logo.png"
              />
            </a>
        </q-toolbar-title>
        <q-space style="flex-grow: 1 !important" />
        <q-tabs class="gt-sm">
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="Patent Renewal"
            to="/"
          />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="About Us"
            to="/about-us"
            />
            <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="Knowledge Base"
            to="/knowledge-base"
            />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="PATENT.ISTANBUL"
            href="https://patent.istanbul"
          />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="FAQ"
            href="#faqs"
          />

        </q-tabs>
        <q-space />
        <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="Login"
            :to="`/login`"
            v-if="authStore.isAuthenticated == false"
          />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="Register"
            :to="`/register`"
            v-if="authStore.isAuthenticated == false"
          />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            label="Dashboard"
            :to="`/dashboard`"
            v-if="authStore.isAuthenticated == true"
          />

        <div class="lt-md">
          <q-btn
            flat
            dense
            round
            @click="leftDrawerOpen = !leftDrawerOpen"
            icon="menu"
            aria-label="Menu"
          />
        </div>
      </q-toolbar>
    </q-header>
    <q-drawer
      v-model="leftDrawerOpen"
      v-show="leftDrawerOpen"
      side="right"
      bordered
      :width="200"
      :breakpoint="500"
    >
      <q-scroll-area class="fit">
        <q-list padding class="menu-list">
          <q-item clickable v-ripple to="/">
            <q-item-section>  Patent Renewal </q-item-section>
          </q-item>

          <q-item clickable to="/about-us">
            <q-item-section> About Us </q-item-section>
          </q-item>

          <q-item clickable v-ripple href="https://patent.istanbul">
            <q-item-section> PATENT.ISTANBUL </q-item-section>
          </q-item>

          <q-item clickable v-ripple href="#faqs">
            <q-item-section> FAQ </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            clickable
            v-ripple
            :to="`/login`"
            v-if="authStore.isAuthenticated == false"
          >
            <q-item-section> Login </q-item-section>
          </q-item>

          <q-item clickable v-ripple :to="`/register`" v-if="authStore.isAuthenticated == false">
            <q-item-section> Register </q-item-section>
          </q-item>

          <q-item clickable v-ripple :to="`/dashboard`" v-if="authStore.isAuthenticated == true">
            <q-item-section> Dashboard </q-item-section>
          </q-item>

        </q-list>
      </q-scroll-area>
    </q-drawer>

    <CookiesVue v-if="showCookie.value" :isShow="showCookie.value" @closedCookie="isClosed"></CookiesVue>

    <q-page-container>
      <router-view
      />
    </q-page-container>

    <q-footer bordered class="bg-white site-footer">
      <!-- Site footer -->
        <div class="container">
          <div class="row">
            <div class="col-lg-5 col-md-12  col-sm-12 col-xs-12">
              <p style="margin-top:40px" class="about-footer">{{ $t("about-patist-ip") }}
              </p>
              <p class="text-justify footer-address">
                {{ $t("our-address") }}
              </p>
              <br>
              <a href = "mailto: info@patent.istanbul">
                <img
                  src="/images/mail-logo.png"
                  class="q-ml-md"
                  width="50"
                  height="50"
                  alt="patent.istanbul" />
              </a>
              <q-btn unelevated :label="$t('contact-us')" class="footer-contact-us" :to="`${$t('contact-Us')}`"/>
            </div>

            <div class="col-lg-2 col-md-4 col-xs-6">
              <span class="footer-titles"> {{ $t("pages") }}</span>
              <q-list>
                <q-item><q-btn unelevated label="PATENT RENEWAL" :to="`/`" /></q-item>
                <q-item><q-btn unelevated label="ABOUT US" :to="`/about-us`" /></q-item>
                <q-item><q-btn unelevated label="PATENT.ISTANBUL" href="https://patent.istanbul"  /></q-item>
                <q-item><q-btn unelevated label="FAQ" to="#faq" /></q-item>
              </q-list>
            </div>
            <div class="col-lg-2 col-md-4 col-xs-12">
              <span class="footer-titles"> {{ $t("legal") }}</span>
              <q-list>
                <q-item><q-btn unelevated :label="$t('cookie.title')" class="cookie-btn" @click="showCookieOptions" /></q-item>
                <q-item><q-btn unelevated :label="$t('privacy-policy')" class="privacy-policy-btn" to=""  /></q-item>
              </q-list>
            </div>
            <div class="col-lg-3 col-md-4 col-xs-12">
              <span class="footer-titles"> {{ $t("services") }}</span>
              <q-list>
                <q-item><q-btn class="footer-services" unelevated :label="$t('european-patent-validation')" to="https://patent.istanbul/european-patent-validation-in-turkiye"/></q-item>
                <q-item><q-btn class="footer-services" unelevated :label="$t('pct-entry')" to="https://patent.istanbul/pct-national-phase-entry-in-turkiye" /></q-item>
                <q-item><q-btn class="footer-services" unelevated :label="$t('annuity.title')" to="https://patent.istanbul/patent-renewals-in-turkiye" /></q-item>
                <q-item><q-btn unelevated :label="$t('api')" to="https://patent.istanbul/api" /></q-item>
              </q-list>
            </div>
          </div>
          <hr>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
              <p class="copyright-text">{{ $t("copyright") }}
                <a href="#">{{ $t("patistipturkiye") }}</a>.
              </p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 iyzico-image">
              <img src="/images/logo_band_colored.png"
                   class="iyzico-image"
                   loading="lazy"
                   alt="patent.istanbul"
                   width="400"
                   height="30"
              />
            </div>
          </div>
        </div>
    </q-footer>
  </q-layout>
</template>

<script>
import { ref,defineAsyncComponent  } from "vue";
import { api } from "boot/axios";
import { useAuthStore } from 'src/stores/useAuthStore'
import {useRouter} from "vue-router";
import {createI18n} from "vue-i18n";
let stringOptions = [];

export default {
  components: {
    CookiesVue: defineAsyncComponent(() => import("components/Cookies.vue")),
  },
  setup() {
    const user = ref(useAuthStore().user)
    const authStore = useAuthStore();
    const showCookie = ref(false);
    const options = ref(stringOptions);
    const data = ref(null);
    const option = ref(null);
    const leftDrawerOpen = ref(false);
    const search = ref(null);
    const router = useRouter()
    const logoImageProps = ref({
      class: 'q-ml-lg',
      alt: 'patent.istanbul',
    })
    const locale = ref( 'en');
    const i18n = createI18n({
      legacy: false,
      locale: locale.value,
      fallbackLocale: "en",
    });

    function showCookieOptions(){
      showCookie.value = ref(true);
    }

    return {
      authStore,
      user,
      i18n,
      locale,
      logoImageProps,
      id:ref(null),
      showCookie,
      showCookieOptions,
      data,
      option,
      leftDrawerOpen,
      model: ref(null),
      options,
      router,
    };
  },
  methods:{
    isClosed(value){
      this.showCookie.value = false;
    },
  },
};
</script>
