<template>
  <q-page class="row  items-center select-section">
    <div class="animation-wrapper">
      <div class="particle particle-1"></div>
      <div class="particle particle-2"></div>
      <div class="particle particle-3"></div>
      <div class="particle particle-4"></div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-5 col-xs-12 text-right  looking-for">
      <h1 class="landing-page-text"> {{ $t("home.page.title") }}</h1>
    </div>
    <div class="col-xl-4 col-lg-5 col-md-5 col-sm-6 col-xs-12 text-center self-center select-option">
      <q-select
        class="qselect"
        id="select"
        v-model="model"
        :options="options"
        label="."
        size="sm"
        @update:model-value="value => changeRoute(value)"
      >
        <template v-slot:option="scope">
          <q-item v-if="!scope.opt.group"
                  v-bind="scope.itemProps"
                  v-on="scope.itemEvents"
                  class="select-option-items"
          >
            <q-item-section avatar>
              <q-icon :name="scope.opt.icon" ></q-icon>
            </q-item-section>
            <q-item-section>
              <q-item-label v-html="scope.opt.label" ></q-item-label>
              <q-item-label caption>{{ scope.opt.description }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="scope.opt.group"
                  v-bind="scope.itemProps"
                  v-on="scope.itemEvents"
          >
            <q-item-label class="main-header-options" header>{{ scope.opt.group }}</q-item-label>
          </q-item>
        </template>
      </q-select>

      <q-list dense padding flat class="rounded-borders">
        <q-item v-for="option in selectOptions" :key="option.index">
          <q-item-section>
            <q-btn
              class="options"
              flat
              color="grey-1"
              align="left"
              :label="option.label"
              disable
            />
            <q-list>
              <q-item v-for="opt in option.options" :key="opt.index">
                <q-item-section v-if="opt.text !== $t('ask.away')">
                  <q-btn
                    class="select-options"
                    flat
                    color="grey-1"
                    align="left"
                    :label="opt.text"
                    :to="opt.route"
                  />
                </q-item-section>
                <q-item-section v-else>
                  <q-btn
                    class="select-options"
                    flat
                    color="grey-1"
                    align="left"
                    :label="opt.text"
                    @click="contactModal = true"
                  />
                </q-item-section>
              </q-item>
            </q-list>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </q-page>
  <q-dialog v-model="contactModal">
    <q-card class="contact-card-index">
      <ContactPage style="padding-top:50px" :msg="message" ></ContactPage>
    </q-card>
  </q-dialog>
</template>

<script>
import { ref,defineAsyncComponent } from "vue";
import {useMeta} from "quasar";
import {useI18n} from "vue-i18n";
const metaData = {
  title: 'Home',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: 'canonical',
      href: 'https://patent.istanbul/'
    }
  ],
}

export default {
  components: { ContactPage: defineAsyncComponent(() => import('pages/ContactPage.vue')) },
  data() {
    return {
      message: false,
    }
  },
  setup() {
    useMeta(metaData)
    const { t } = useI18n({useScope: 'global'})

    return {
      t,
      contactModal: ref(false),
      model: ref(null),
      selectOptions: [
        {
          visible: true,
          label: t('automated.quote'),
          options: [
            { text: t('ep-validation'), route: t('eupatentvalidation') },
            { text: t('pct-entry'), route: t('pctentry')},
            { text: t('patent-annuity'), route: t('patentrenewals')},
          ],
        },
        {
          visible: true,
          label:  t('get.answer'),
          options: [
            { text:  t('knowledge.base'), route: t('knowledge-base-link') },
            { text: t('ask.away') },
          ],
        },
      ],
      options: [
        {
          group: t('automated.quote'),
          disable: true
        },
        {
          label: t('EP-VALIDATION'),
          value: t('eupatentvalidationvalue'),
          description: t('ep.title'),
          icon: 'navigate_next'
        },
        {
          label: t('PCT-ENTRY'),
          value: t('pctvalue'),
          description: t('pct-entry-in-turkiye'),
          icon: 'navigate_next'
        },
        {
          label: t('PATENT ANNUITY'),
          value: t('PATENT patentrenewalsvalue'),
          description: t('patentrenewalinturkiye'),
          icon: 'navigate_next'
        },
        {
          group: t('get.answer'),
          disable: true
        },
        {
          label: t('knowledge.base'),
          value: t('knowledge-basevalue'),
          description: t('knowledge-base'),
          icon: 'navigate_next'
        },
        {
          label: t('ask.away'),
          value: t('ask-away-value'),
          description: t('contact-us'),
          icon: 'navigate_next'
        }
      ]
    };
  },
  methods: {
    changeRoute(val) {
      if(val.value !== t('ask-away-value')) {
        this.$router.push("/" + val.value);
      }
      else{
        this.contactModal = true;
      }
    },
  },
  computed: {
    isVisible() {
      return this.selectOptions.map((option) => option.visible);
    },
  },
};
</script>
