<template>
  <q-layout ref="layout" view="hHh Lpr lff">
    <q-header elevated class="bg-white text-grey-8">
      <q-toolbar class="GNL__toolbar">
        <q-toolbar-title shrink>
            <a :href="`${$t('home-link')}`">
              <img
                v-bind="logoImageProps"
                width="180"
                height="86"
                src="/images/logo2.webp"
                srcset="/images/logo2-2.webp 2x,
                /images/logo2-3.webp 3x"
              />
            </a>
        </q-toolbar-title>
        <q-space style="flex-grow: 1 !important" />
        <q-tabs class="gt-sm">
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            :label="$t('ep-validation')"
            :to="`${$t('eupatentvalidation')}`"
          />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            :label="$t('pct-entry')"
            :to="`${$t('pctentry')}`"
          />
          <q-btn
            class="nav-menu"
            flat
            color="dark"
            :label="$t('patent-annuity')"
            :to="`${$t('patentrenewals')}`"
          />
          <q-select
            v-model="locale"
            :options="localeOptions"
            map-options
            style="min-width: 150px"
            @update:model-value="value => changeLanguage(locale.value)"
          />
        </q-tabs>
        <q-space />
        <q-select
          :label="$t('search')"
          class="GNL__toolbar-input search-input gt-xs col-xl-2 col-lg-2"
          outlined
          dense
          use-input
          hide-selected
          fill-input
          input-debounce="0"
          :options="options"
          map-options
          :model-value="id"
          :option-value="'id'"
          :option-label="'title'"
          @filter="filterFn"
          @update:model-value="updateFn"
          color="bg-grey-7 shadow-1"
        >
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey"> {{ $t("no-result") }}</q-item-section>
            </q-item>
          </template>
          <template v-slot:option="options">
            <q-item clickable v-ripple>
              <q-icon
                color="primary"
                name="navigate_next"
                style="margin-top: 21px; margin-right: 10px; font-size: 20px"
              />
              <q-item-section
                @click="updateFn(options.opt.slug, options.opt.question_link, options.opt.type)"
              >
                <q-item-label
                  v-for="option in options"
                  :key="option.id"
                  class="searchinput"
                >
                  <q-item-label
                    caption
                    style="margin-bottom: 5px; color: #71797e"
                    >{{ option.type }}</q-item-label
                  >
                  <q-item-section
                    style="max-height: 100px; color: #5371a6"
                    v-if="option.type === 'Services'"
                    >{{ option.title }}</q-item-section
                  >
                  <q-item-section style="max-height: 100px; color: black" v-if="option.type === 'Knowledge Base'">{{
                    option.question
                  }}</q-item-section>
                  <q-item-section
                    style="max-height: 100px; color: #4998e5"
                    v-if="option.type === 'Frequently asked questions'"
                    >{{ option.title }}</q-item-section
                  >
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-separator />
          </template>
        </q-select>

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
      <q-item clickable v-ripple class="xs">
        <q-item-section>
          <q-select
            :label="$t('Search')"
            outlined
            dense
            use-input
            hide-selected
            fill-input
            input-debounce="0"
            :options="options"
            map-options
            :model-value="id"
            :option-value="'id'"
            :option-label="'title'"
            @filter="filterFn"
            @update:model-value="updateFn"
            color="bg-grey-7 shadow-1"
          >
            <template v-slot:no-option>
              <q-item>
                <q-item-section class="text-grey"> {{ $t("no-result") }}</q-item-section>
              </q-item>
            </template>
            <template v-slot:option="options">
              <q-item clickable v-ripple>
                <q-icon
                  color="primary"
                  name="navigate_next"
                  style="margin-top: 21px; margin-right: 10px; font-size: 20px"
                />
                <q-item-section
                  @click="updateFn(options.opt.slug, options.opt.question_link, options.opt.type)"
                >
                  <q-item-label
                    v-for="option in options"
                    :key="option.id"
                    class="searchinput"
                  >
                    <q-item-label
                      caption
                      style="margin-bottom: 5px; color: #71797e"
                    >{{ option.type }}</q-item-label
                    >
                    <q-item-section
                      style="max-height: 100px; color: #5371a6"
                      v-if="option.type === 'Services'"
                    >{{ option.title }}</q-item-section
                    >
                    <q-item-section style="max-height: 100px; color: black" v-if="option.type === 'Knowledge Base'">{{
                        option.question
                      }}</q-item-section>
                    <q-item-section
                      style="max-height: 100px; color: #4998e5"
                      v-if="option.type === 'Frequently asked questions'"
                    >{{ option.title }}</q-item-section
                    >
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-separator />
            </template>
          </q-select>
        </q-item-section>
      </q-item>

      <q-scroll-area class="fit">
        <q-list padding class="menu-list">
          <q-item clickable v-ripple :href="`${$t('home-link')}`">
            <q-item-section>  {{ $t("home") }} </q-item-section>
          </q-item>

          <q-item clickable :href="`${$t('about-link')}`">
            <q-item-section> {{ $t("about") }} </q-item-section>
          </q-item>

          <q-item clickable v-ripple :href="`${$t('knowledge-base-link')}`">
            <q-item-section> {{ $t("knowledge-base") }} </q-item-section>
          </q-item>

          <q-item clickable v-ripple :href="`${$t('faqS')}`">
            <q-item-section> {{ $t("faqs") }} </q-item-section>
          </q-item>

          <q-separator />

          <q-item
            clickable
            v-ripple
            :href="`${$t('eupatentvalidation')}`"
          >
            <q-item-section> {{ $t("ep-validation") }} </q-item-section>
          </q-item>

          <q-item clickable v-ripple :href="`${$t('pctentry')}`">
            <q-item-section> {{ $t("pct-entry") }} </q-item-section>
          </q-item>

          <q-item clickable v-ripple :href="`${$t('patentrenewals')}`">
            <q-item-section> {{ $t("patent-annuity") }} </q-item-section>
          </q-item>
          <q-separator />
          <q-item clickable v-ripple :href="`${$t('contact-Us')}`">
            <q-item-section> {{ $t("contact") }} </q-item-section>
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
                <q-item><q-btn unelevated :label="$t('home')" :to="`${$t('home-link')}`"/></q-item>
                <q-item><q-btn unelevated :label="$t('about')" :to="`${$t('about-link')}`" /></q-item>
                <q-item><q-btn unelevated :label="$t('services')" :to="`${$t('services-link')}`" /></q-item>
                <q-item><q-btn unelevated :label="$t('knowledge-base')" :to="`${$t('knowledge-base-link')}`"  /></q-item>
                <q-item><q-btn unelevated :label="$t('faqs')" :to="`${$t('faqS')}`" /></q-item>
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
                <q-item><q-btn class="footer-services" unelevated :label="$t('european-patent-validation')" :to="`${$t('eupatentvalidation')}`"/></q-item>
                <q-item><q-btn class="footer-services" unelevated :label="$t('pct-entry')" :to="`${$t('pctentry')}`" /></q-item>
                <q-item><q-btn class="footer-services" unelevated :label="$t('annuity.title')" :to="`${$t('patentrenewals')}`" /></q-item>
                <q-item><q-btn unelevated :label="$t('api')" :to="`${$t('Api')}`" /></q-item>
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
import {useRouter} from "vue-router";
import {createI18n, useI18n} from "vue-i18n";
let stringOptions = [];

export default {
  components: {
    CookiesVue: defineAsyncComponent(() => import("components/Cookies.vue")),
  },
  setup() {
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
    const { t } = useI18n({useScope: 'global'})
    const i18n = createI18n({
      legacy: false,
      locale: locale.value,
      fallbackLocale: "en",
    });

    const localeOptions = [
      { value: "en", label: "English" },
      { value: "tr", label: "Turkish"},
      { value: "de", label: "German" },
      { value: "fr", label: "French" },
      { value: "es", label: "Spanish" },
      { value: "it", label: "Italian" },
      { value: "ja", label: "Japanese" },
      { value: "ko", label: "Korean" },
    ];

    function showCookieOptions(){
      showCookie.value = ref(true);
    }

    return {
      t,
      localeOptions,
      i18n,
      locale,
      logoImageProps,
      id:ref(null),
      showCookie,
      showCookieOptions,
      search,
      data,
      option,
      leftDrawerOpen,
      model: ref(null),
      options,
      router,
      filterFn(val, update) {


        const url = window.location.href;
        const languages = ['tr', 'ko', 'de', 'ja', 'it', 'fr'];

        const languageCode = url.split('/')[3];

        if (languages.includes(languageCode)) {
          var lang = languageCode;
        } else {
          var lang = "en";
        }


        if (val.length > 3) {
          api.get(`/api/v1/live-search/${lang}/${val}`).then((res) => {
            stringOptions = res.data;
            update(() => {
              options.value = stringOptions.data;
            });
            search.value = val;
          });
        }
        setTimeout(() => {
          update(() => {
            options.value = stringOptions.data;
          });
        }, 2000);
      },
      updateFn(val, path, type) {
        const url = window.location.href;
        const languages = ['tr', 'ko', 'de', 'ja', 'it', 'fr'];
        const languageCode = url.split('/')[3];

        let lang = "";
        if (languages.includes(languageCode)) {
          lang = languageCode;
        }
        // faqS

        if (type === "Services" || type === "Frequently asked questions") {
          console.log(val);
          router.push(`${t('faqS')}`);
        } else {
          router.push({
            path: '/' + lang + '/' + `${t('knowledge-base-link')}/${val}/${path}`
          });
        }
      },
      setModel(val) {
        search.value = val;
      },
    };
  },
  methods:{
    isClosed(value){
      this.showCookie.value = false;
    },
    changeLanguage(locale) {
      // Update the locale for the i18n instance
      // Update the locale for the i18n instance
      this.$i18n.locale = locale;

    // Construct the new route path with the selected language
      const newPath = `/${locale}`;

    // Redirect to the new route path
      this.$router.push(newPath);
    }
  },
  beforeMount() {

    var link = document.location.href.split('/');

    if(link[3] === "es"){
      this.locale="es";
      this.$i18n.locale = "es"
    }
    else if(link[3] === "tr") {
      this.$i18n.locale = "tr"
      this.locale="tr";
    }
    else if (link[3] === "de"){
      this.$i18n.locale ="de"
      this.locale="de";
    }
    else if (link[3] === "ja"){
      this.$i18n.locale ="ja"
      this.locale="ja";
    }
     else if (link[3] === "ko"){
      this.$i18n.locale ="ko"
      this.locale="ko";
    }
    else if (link[3] === "it"){
      this.$i18n.locale ="it"
      this.locale="it";
    }
    else if (link[3] === "fr"){
      this.$i18n.locale ="fr"
      this.locale="fr";
    }
    else {
      this.$i18n.locale = "en"
      this.locale="en";
    }
  },
};
</script>
