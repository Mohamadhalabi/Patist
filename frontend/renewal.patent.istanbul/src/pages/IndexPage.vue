<template>
  <q-page class="main-container">
    <section class="intro-nine-Wrap hero" id="home">
      <div class="container">
        <div class="row">
          <div class="col-md-8 q-my-auto">
            <div class="q-mx-xl q-my-md">
              <h2 class="text-h2">Patent Renewal In Türkiye</h2>
              <p class="text-20 mb-4">Turkish Patents automation system</p>
              <div class="mb-5 lead">
                <p>
                  Turkish patents and utility models require annual renewing at
                  TÜRKPATENT. With our fast and reliable renewing service, your
                  patents and utililty models annuities are taken care of, while
                  you focus on your core business. Join others that smoothly
                  automate their renewal processes via Patent.Istanbul.
                </p>
              </div>
              <div>
                <q-btn
                  class="q-mx-md half-button btn-learn-more q-ma-md"
                  href="#patent-renewal"
                  style="cursor: pointer"
                >
                  Learn More
                </q-btn>
                <q-btn
                  class="q-mx-md half-button btn-contact q-ma-md"
                  label="Go to dashboard"
                  :to="`dashboard`"
                />
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <q-img
              loading="lazy"
              style="height: 100%"
              src="/images/hero2.webp"
              mode="cover"
            />
          </div>
        </div>
      </div>
    </section>
    <div class="container" id="patent-renewal">
      <div class="card-header q-pa-md text-center">
        <div class="row justify-center align-center">
          <h2 class="h2-text page-header">Patent Renewal In Türkiye</h2>
        </div>
      </div>
      <div class="section about">
        <div class="row justify-center">
          <div class="col-xl-4 col-lg-5 col-md-4 col-sm-6 col-xs-9 q-pa-md">
            <q-img
              class="about-us-main-image"
              src="/images/about-us.webp"
              fit="cover"
            />
          </div>
          <div
            class="col-xl-5 col-lg-5 col-md-7 col-sm-8 col-xs-10 q-pa-md q-my-auto"
          >
            <p class="text-subtitle2 page-title">
              Automate your Turkish patent renewals
            </p>
            <p class="about-us-description text-body1">
              With a team specialized in patent renewals, Patent.Istanbul simplifies and manages your renewal process for Turkish patents and utility models. Simply add your patents to our system and start automation.
            </p>
            <p class="text-subtitle2 page-title">Always know your costs</p>
            <p class="about-us-description text-body1">
              Patent.Istanbul calculates patent renewal fees automatically. You always know your renewal costs and you can plan ahead. There are no hidden costs.
            </p>
          </div>
        </div>
        <div class="row justify-center">
          <div class="col-md-9 q-my-md q-pa-md">
            <p class="text-subtitle2 page-title">Fast and reliable</p>
            <p class="about-us-description text-body1">
              Leave Turkish Patents annuity management to us and focus on your core business. Patent.Istanbul will take care of every aspect related to patent renewals, including reminding in a timely manner the next year.
            </p>
          </div>
        </div>
        <div class="row justify-center">
          <div
            class="col-xl-5 col-lg-5 col-md-7 col-sm-8 col-xs-10 q-pa-md q-my-auto"
          >
            <p class="text-subtitle2 page-title">
              Moving Portfolios and Further Automation
            </p>
            <p class="about-us-description text-body1">
              If you would like to transfer your portfolio in bulk, we provide additional services for easy onboarding. Our team can add your portfolio to your account for free and if you would like further automation integrated to your own software, we provide an API for IP services in Türkiye.
            </p>
            <p class="text-subtitle2 page-title">Support Service</p>
            <p class="about-us-description text-body1">
              We provide continuous support to our clients at Patent.Istanbul. Our support service is quick and to the point. Get in touch for any queries.
            </p>
          </div>
          <div class="col-xl-4 col-lg-5 col-md-4 col-sm-6 col-xs-9 q-pa-md">
            <q-img
              class="about-us-main-image"
              src="/images/about-us-2.jpg"
              fit="cover"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="container" id="services">
      <div class="section services">
        <div class="card-header q-pa-md text-center">
          <div class="row justify-center align-center">
            <h2 class="h2-text page-header">
              Other {{ $t("services.title") }}
            </h2>
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
              <q-btn
                color="primary"
                :label="[$t('go.to.service')]"
                class="plan-choose"
                href="https://monitor.patent.istanbul/"
              />
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
              <q-btn
                color="primary"
                :label="[$t('go.to.service')]"
                class="plan-choose"
                href="https://takip.marka.legal/"
              />
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">
                  {{ $t("turkish.patent.application.title") }}
                </h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("turkish.patent.application.option1") }}</li>
                <li>{{ $t("turkish.patent.application.option2") }}</li>
                <li>{{ $t("turkish.patent.application.option3") }}</li>
              </ul>
              <q-btn
                color="primary"
                :label="[$t('go.to.service')]"
                class="plan-choose"
                to="/dashboard/new-application"
              />
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">
                  {{ $t("ep-validation.in.turkey.title") }}
                </h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("ep-validation.in.turkey.option1") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option2") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option3") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option4") }}</li>
                <li>{{ $t("ep-validation.in.turkey.option5") }}</li>
              </ul>
              <q-btn
                color="primary"
                :label="[$t('go.to.service')]"
                class="plan-choose"
                href="https://patent.istanbul/european-patent-validation-in-turkiye"
              />
            </div>
          </div>
          <div class="col-lg-3 col-md-5 col-sm-8 col-xs-12 q-pa-md q-mr-lg">
            <div class="plan">
              <div class="plan-header">
                <h3 class="plan-heading">
                  {{ $t("pct.national.entry.in.turkey.title") }}
                </h3>
              </div>
              <ul class="plan-feature">
                <li>{{ $t("pct.national.entry.in.turkey.option1") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option2") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option3") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option4") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option5") }}</li>
                <li>{{ $t("pct.national.entry.in.turkey.option6") }}</li>
              </ul>
              <q-btn
                color="primary"
                :label="[$t('go.to.service')]"
                class="plan-choose"
                href="https://patent.istanbul/pct-national-phase-entry-in-turkiye"
              />
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
              <q-btn
                color="primary"
                :label="[$t('go.to.service')]"
                class="plan-choose"
                href="https://patent.istanbul/patent-renewals-in-turkiye"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container" id="faqs">
      <div class="row justify-center">
        <div class="col-xl-12 text-center">
          <h3 className="h3-text page-header">
            {{ $t("faq.title") }}
          </h3>
        </div>
      </div>
      <div class="section">
        <div class="q-pa-md">
          <div class="row justify-center">
            <div class="col-md-10">
              <div class="q-ma-lg q-pa-md">
                <q-list bordered class="rounded-borders">
                  <q-expansion-item
                    v-for="item in faqs"
                    :key="item.id"
                    expand-separator
                    icon="help_outline"
                    color="primary"
                    :label="item.title"
                  >
                    <q-card>
                      <q-card-section v-html="item.answer"> </q-card-section>
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
import { useMeta } from "quasar";

const metaData = {
  title: "Patent Renewal In Türkiye",
  titleTemplate: (title) => `${title}`,
};

export default {
  setup() {
    useMeta(metaData);
    return {
      faqs: ref([]),
    };
  },
  mounted: function () {
    this.$nextTick(function () {
      api
        .get("/api/v1/faqs?language=en")
        .then((res) => {
          this.faqs = res.data;
        })
        .catch((err) => {});
    });
  },
};
</script>

<style scoped>
.hero {
  color: #fff;
  background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);
}
.intro-nine-Wrap {
  color: #fff;
  background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);
}
.half-button {
  border: 1px solid;
  border-radius: 3px 15px !important;
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
.mb-4 {
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
