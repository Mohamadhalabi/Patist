<template>
  <q-page class="main-container">
    <div class="container">
      <div class="section services">
        <div class="card-header q-pa-md text-center">
          <div class="row justify-center align-center">
            <h3 class="h1-text page-header ">
              {{ $t("services.title") }}
            </h3>
          </div>
        </div>
        <div class="row q-pa-md justify-center grid plan-options">
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">
                  {{ $t("patent.monitoring.title") }}
                </h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("patent.monitoring.option1") }}</li>
                <li>{{ $t("patent.monitoring.option2") }}</li>
                <li>{{ $t("patent.monitoring.option3") }}</li>
                <li>{{ $t("patent.monitoring.option4") }}</li>
                <li>{{ $t("patent.monitoring.option5") }}</li>
              </ul>
              <q-btn color="primary"

                     :label="[$t('go.to.service')]"
                     class="plan-choose"
                     @click="scrollMeTo('porto1')"/>
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">
                  {{ $t("fto.title") }}
                </h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("fto.option1") }}</li>
                <li>{{ $t("fto.option2") }}</li>
                <li>{{ $t("fto.option3") }}</li>
              </ul>
              <q-btn color="primary" :label="[$t('go.to.service')]"
                     class="plan-choose" @click="scrollMeTo('porto2')"/>
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">{{ $t("turkish.patent.application.title") }}</h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("turkish.patent.application.option1") }}</li>
                <li>{{ $t("turkish.patent.application.option2") }}</li>
                <li>{{ $t("turkish.patent.application.option3") }}</li>
              </ul>
              <q-btn color="primary" :label="[$t('go.to.service')]"
                     class="plan-choose" @click="scrollMeTo('porto3')"/>
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">{{ $t("ep-validation.in.turkey.title") }}</h3>
              </div>
              <ul class="plan-feature" >
                <li>{{ $t("ep-validation.in.turkey.option1") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option2") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option3") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option4") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option5") }}</li>
              </ul>
              <q-btn color="primary" :label="[$t('go.to.service')]"
                     class="plan-choose" to="/european-patent-validation-in-turkiye" />
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">{{ $t("pct.national.entry.in.turkey.title") }}</h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("pct.national.entry.in.turkey.option1") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option2") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option3") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option4") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option5") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option6") }}</li>
              </ul>
              <q-btn color="primary" :label="[$t('go.to.service')]"
                     class="plan-choose" to="/pct-national-phase-entry-in-turkiye" />
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">{{ $t("patent.renewals.title") }}</h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("patent.renewals.option1") }}</li>
                <li>{{ $t("patent.renewals.option2") }}</li>
                <li>{{ $t("patent.renewals.option3") }}</li>
                <li>{{ $t("patent.renewals.option4") }}</li>
              </ul>
              <q-btn color="primary" :label="[$t('go.to.service')]"
                     class="plan-choose" to="/patent-renewals-in-turkiye" />
            </div>
          </div>
        </div>
        <div class="row justify-center" ref="porto1">
          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">{{ $t("turkish.patent.application.title") }}</p>
            <q-list>
              <q-item>
                <q-item-section class="about-us-description">
                  {{ $t("turkish.patent.application.description") }}
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section class="about-us-description">
                  {{ $t("contact.button.description") }}
                </q-item-section>
              </q-item>
              <br>
              <q-btn
                class="button-font"
                style="margin-left: 2%"
                color="primary"
                :label="[$t('about.goal.title')]"
                @click="inception = true;getSubject('Turkish Patent Application',contactMessage='I want to know more about Turkish patent application')"
              />

            </q-list>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img src="/images/copyright-claim.jpg" class="services-image" />
          </div>
        </div>
        <div class="row justify-center" ref="porto2">
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">
              {{ $t("fto.title") }}
            </p>
            <q-list>
              <q-item>
                <q-item-section class="about-us-description">
                  {{ $t("fto.description") }}
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-section class="about-us-description">
                    {{ $t("contact.button.description") }}
                  </q-item-section>
                </q-item-section>
              </q-item>
              <br>
              <q-btn
                class="button-font"
                style="margin-left: 2%"
                color="primary"
                :label="[$t('about.goal.title')]"
                @click="inception = true;
                getSubject('Freedom to Operate Search in Türkiye',contactMessage='I want to know more about FTO')"
              />
            </q-list>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img src="/images/search.jpg" class="services-image" />
          </div>
        </div>
        <div class="row justify-center" ref="porto3">
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us" >
            <p class="text-subtitle2 page-title about-us">
              {{ $t("patent.monitoring.title") }}
            </p>
            <q-list>
              <q-item>
                <q-item-section class="about-us-description">
                  {{ $t("patent.monitoring.services.description") }}
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-section class="about-us-description">
                    {{ $t("contact.button.description") }}
                  </q-item-section>
                </q-item-section>
              </q-item>
              <br>
              <q-btn
                class="button-font"
                style="margin-left: 2%"
                color="primary"
                :label="[$t('about.goal.title')]"
                @click="inception = true;
                getSubject('Patent Monitoring in Türkiye',contactMessage='I want to know more about patent monitoring in Türkiye')"
              />
            </q-list>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img src="/images/monitoring-service.jpg" class="services-image" />
          </div>
        </div>
      </div>
    </div>
  </q-page>
  <q-dialog v-model="inception">
    <q-card class="contact-card-index">
      <ContactPage
        :userName="userName"
        :userPhone="userPhone"
        :userEmail="userEmail"
        :serviceSubject="subject"
        :msg="message"
        :contactMsg="contactMessage"
        @changeName="getName"
        @changePhone="getPhone"
        @changeEmail="getEmail"
        style="padding-top:50px"
      >
      </ContactPage>
    </q-card>
  </q-dialog>
</template>
<script>
import { ref } from "vue";
import ContactPage from "pages/ContactPage";
import {useMeta} from "quasar";

const metaData = {
  title: 'Services',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: 'canonical',
      href: 'https://patent.istanbul/services'
    }
  ],
}
export default {
  components: {ContactPage},
  data() {
    return {
      message: false,
      contactMessage: "",
      userName: "",
      userPhone: "",
      userEmail: "",
    }
  },
  methods: {
    getName(event){
      this.userName = event;
    },
    getPhone(event){
      this.userPhone = event;
    },
    getEmail(event){
      this.userEmail = event;
    },
    scrollMeTo(refName) {
      var element = this.$refs[refName];
      var top = element.offsetTop -100;

      window.scrollTo({
        top,
        behavior: 'smooth'
      });
    },
    getSubject(opt,Message){
      this.subject = opt;
      this.contactMessage = Message;
    }
  },
  setup() {
    useMeta(metaData)
    return {
      subject: ref(''),
      inception: ref(false),
    };
  },
};
</script>
