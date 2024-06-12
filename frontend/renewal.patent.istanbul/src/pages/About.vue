<template>
  <q-page class="main-container">
    <div class="container">
      <div class="section about">
        <div class="row justify-center">
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img
              class="about-us-main-image"
              src="/images/about-us.jpg"
              fit="cover" />
          </div>
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">
              {{ $t("about.patent.istanbul.title") }}
            </p>
            <p class="about-us-description text-body1">
              {{ $t("about.patent.istanbul.description") }}
            </p>
            <p class="text-subtitle2 page-title about-us">
              {{ $t("about.goal.title") }}
            </p>
            <p class="about-us-description text-body1">
              {{ $t("about.goal.description") }}

            </p>
          </div>
        </div>
        <div class="row justify-center">
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">
              {{ $t("firm.title") }}
            </p>
            <q-list>
              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>
                <q-item-section class="about-us-description">
                  {{ $t("firm.option1") }}
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("firm.option2") }}
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("firm.option3") }}
                </q-item-section>
              </q-item>
            </q-list>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img
              src="/images/firm.jpg"
              class="about-us-image"
            >
            </q-img>
          </div>
        </div>
        <div class="row justify-center reverse-wrap">
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9  q-pa-md about-us float-right">
            <q-img
              src="/images/istanbul.jpg"
              fit="cover"
              class="about-us-image" />
          </div>
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">
              {{ $t("why.patent.istanbul") }}
            </p>
            <q-list>
              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("why.patent.istanbul.option1") }}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("why.patent.istanbul.option2") }}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section  class="about-us-description">
                  {{ $t("why.patent.istanbul.option3") }}
                  <span style="font-style: italic;display: contents">{{ $t("proust.patent") }}
                  </span>
                </q-item-section>
              </q-item>
            </q-list>
          </div>
        </div>

        <div class="row justify-center">
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">
              {{ $t("compliance.title") }}
            </p>
            <q-list>
              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("compliance.option1") }}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("compliance.option2") }}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("compliance.option3") }}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("compliance.option4") }}
                </q-item-section>
              </q-item>
            </q-list>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img src="/images/compliance.jpg" class="about-us-image"> </q-img>
          </div>
        </div>

        <div class="row justify-center reverse-wrap">
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-9 q-pa-md about-us">
            <q-img src="/images/team.jpg" fit="cover" class="about-us-image"> </q-img>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8 col-xs-10 q-pa-md about-us">
            <p class="text-subtitle2 page-title about-us">
              {{ $t("team.title") }}
            </p>
            <q-list>
              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("team.option1") }}
                  <span style="font-style: italic;display: contents">
                    {{ $t("proust.patent") }}
                  </span>
                  {{ $t("as.their.own")}}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description">
                  {{ $t("team.option2") }}
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section avatar>
                  <q-icon color="primary" name="verified" />
                </q-item-section>

                <q-item-section class="about-us-description" style="display: inline-block">
                    {{ $t("team.option3") }}
                  <a style="text-decoration: none" target="_blank" href="https://proustpatent.com/#team">{{ $t("the.team") }}</a>
                </q-item-section>
              </q-item>
            </q-list>
          </div>
        </div>
      </div>
<!--      <br><br><br>-->
    </div>
  </q-page>
</template>

<script>
import { useMeta } from 'quasar'

const metaData = {
  title: 'About Us',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
}

export default {
  setup () {
    useMeta(metaData)
  }
}
</script>
