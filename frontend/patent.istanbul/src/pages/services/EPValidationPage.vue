<template>
  <q-page class="main-container">
    <div class="container">
      <div class="row">
        <div class="col-md-10 q-mx-auto col-xs-12">
          <h1 class="text-h2 text-center text-primary page-header">
            {{ $t("ep.title") }}
            <q-btn
              flat
              round
              icon="info"
              @click="guide = true"
              :title="[$t('ep.validation.steps')]"
            />
          </h1>
          <p class="text-center service-description">
            {{ $t("ep.description") }}
          </p>
          <button
            class="float-right feedback-btn feedback-txt"
            @click="fixed = true"
          >
            {{ $t("feedback") }}
          </button>
          <br /><br />
          <q-stepper
            v-model="step"
            ref="stepper"
            color="primary"
            animated
            vertical
            header-nav
            class="q-ma-md"
          >
            <!-- Step 1 -->
            <q-step :name="1" title="Patent Number" :done="step > 1">
              <div class="q-pa-md">
                <q-form @submit="step1Submit()">
                  <q-input
                    v-model="patentNumber"
                    filled
                    type="text"
                    :label="[$t('ep.step.1.input.label')]"
                    :hint="[$t('ep.step.1.input.hint')]"
                    mask="XXXXXXXXXXXXXXX"
                    class="q-mb-md"
                    lazy-rules
                    :rules="[
                      (val) =>
                        (val && val.length > 4) || $t('ep.step.1.input.error'),
                    ]"
                  >
                    <template v-slot:append>
                      <q-icon name="search" />
                    </template>
                  </q-input>
                  <q-stepper-navigation>
                    <q-btn
                      :loading="loading"
                      type="submit"
                      color="primary"
                      label="Query"
                    />
                  </q-stepper-navigation>
                </q-form>
              </div>
            </q-step>
            <!-- Step 2 -->
            <q-step
              :name="2"
              :title="[$t('patent.info')]"
              :done="step > 2"
              :header-nav="virtualStep > 1"
            >
              <div style="margin-top: 20px">
                <p class="text-primary">
                  {{ $t("ep.step.1.title") }} :
                  <strong class="text-dark">{{ this.patentNumber }}</strong>
                </p>
                <p class="text-primary">
                  {{ $t("filling.date") }}
                  <strong class="text-dark">{{
                      this.epoData.response.bibliographicData.publicationDate
                    }}</strong>
                </p>
                <p class="text-primary">
                  {{ $t("ep.step.2.label.inventionTitle") }} :
                  <strong class="text-dark">{{
                      this.epoData.response.bibliographicData.inventionTitle
                    }}</strong>
                </p>
                <p class="text-primary">
                  {{ $t("ep.step.2.label.applicant") }} :
                  <strong class="text-dark">{{
                      this.epoData.response.bibliographicData.applicant
                    }}</strong>
                </p>
              </div>
              <!-- Case 1# Expired -->
              <div
                v-if="
                  this.epoData.response.bibliographicData.status == 'expired'
                "
              >
                <p class="text-primary">
                  {{ $t("patent.renewals.status") }} :
                  <span class="text-dark"
                  ><strong>{{ $t("patent.cannot.renewed") }}.</strong>
                    {{ $t("please.contact") }}.</span
                  >
                </p>
                <q-btn
                  color="primary"
                  :label="[$t('ep.contact.us')]"
                  class="q-ma-md"
                  @click="$router.replace('/contact')"
                />
              </div>
              <!-- Case 1# End -->
              <!-- Case 2# Very Close To Expire -->
              <div v-if="
                  this.epoData.response.bibliographicData.status == 'very-close-to-expire'
                ">
                <p class="text-primary">
                <span class="text-dark"><strong>{{ $t("ep.very-close-to-expire") }}</strong></span
                >
                </p>
                <q-btn
                  color="primary"
                  :label="[$t('ep.contact.us')]"
                  class="q-ma-md"
                  @click="$router.replace('/contact')"
                />
              </div>
              <!-- Case 2# End -->
              <q-stepper-navigation
                v-if="
                  this.epoData.response.bibliographicData.status == 'valid' ||
                  this.epoData.response.bibliographicData.status ==
                    'close-to-expire'
                "
              >
                <q-btn
                  v-if="step > 0"
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('back')]"
                  class="q-ml-sm"
                />
                <q-btn
                  @click="stepperNext()"
                  color="primary"
                  :label="step === 6 ? 'Finish' : 'Continue'"
                />
              </q-stepper-navigation>
            </q-step>
            <!-- Step 3 -->
            <q-step
              :name="3"
              :title="[$t('agent.info')]"
              :done="step > 3"
              :header-nav="virtualStep > 2"
            >
              <div class="q-pa-md">
                <q-form @submit="step3Submit()">
                  <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 q-pa-md">
                      <q-input
                        filled
                        v-model="name"
                        :label="[$t('pct.step.4.input.name.label')]"
                        :hint="[$t('pct.step.4.input.name.hint')]"
                        lazy-rules
                        :rules="[
                          (val) =>
                            (val && val.length > 0) ||
                            $t('pct.step.4.input.name.error'),
                        ]"
                      />
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 q-pa-md">
                      <q-input
                        filled
                        v-model="phone"
                        :label="[$t('pct.step.4.input.phone.label')]"
                        :hint="[$t('pct.step.4.input.phone.hint')]"
                        maxlength="15"
                        :rules="[
                          (val) =>
                            (val && val.length > 4) ||
                            $t('pct.step.4.input.phone.error'),
                        ]"
                      />
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 q-pa-md">
                      <q-input
                        filled
                        v-model="email"
                        :label="[$t('pct.step.4.input.email.label')]"
                        :hint="[$t('pct.step.4.input.email.hint')]"
                        lazy-rules
                        :rules="[
                          (val) => !!val || $t('pct.step.4.input.email.error'),
                          isValidEmail,
                        ]"
                      />
                    </div>
                  </div>
                  <q-stepper-navigation>
                    <q-btn
                      flat
                      color="primary"
                      @click="stepperPrevious()"
                      :label="[$t('pct.step.4.button.prev')]"
                      class="q-ml-sm"
                    />
                    <q-btn
                      type="submit"
                      color="primary"
                      :label="[$t('pct.step.4.button.next')]"
                    />
                  </q-stepper-navigation>
                </q-form>
              </div>
            </q-step>
            <!-- Step 4 -->
            <q-step
              class="summary-step"
              :name="4"
              :title="[$t('result')]"
              :done="step > 4"
              :header-nav="virtualStep > 3"
            >
              <div
                class="col-md-12 result justify-center"
                style="text-align: -webkit-center"
              >
                <table class="demo">
                  <tbody>
                  <tr>
                    <td style="text-align: center">
                      <p class="table-header">Case Information</p>
                    </td>
                    <td style="width: 80%">
                      <p class="table-items">
                        {{ this.fee.serviceFee.item }}
                      </p>
                      <p class="summary-text">
                        {{ $t("ep.order-table.quote.number") }}:
                        <span class="summary-span">{{
                            this.patentNumber
                          }}</span>
                      </p>
                      <p class="summary-text">
                        {{ $t("Title") }}:
                        <span class="summary-span">{{
                            this.epoData.response.bibliographicData
                              .inventionTitle
                          }}</span>
                      </p>
                      <p class="summary-text">
                        {{ $t("ep.order-table.quote.publication-date") }}:
                        <span class="summary-span">{{
                            this.epoData.response.bibliographicData
                              .publicationDate
                          }}</span>
                      </p>
                      <p class="summary-text"></p>
                      <span
                        style="font-size: 16px; font-weight: bold"
                        class="summary-text"
                      >{{ $t("your.ref") }}:
                        </span>
                      <div
                        class="cursor-pointer"
                        style="display: inline-block"
                      >
                          <span
                            style="
                              border-bottom: 1px dotted blue;
                              text-decoration: none;
                              font-size: 16px;
                            "
                            v-if="this.reference != ''"
                          >
                            {{ reference }}
                          </span>
                        <span
                          style="
                              border-bottom: 1px dotted blue;
                              text-decoration: none;
                              font-size: 16px;
                            "
                          v-else
                        >
                          {{ $t("enter.your.reference") }}
                        </span>
                        <q-popup-edit v-model="reference" v-slot="scope">
                          <q-input
                            autofocus
                            dense
                            v-model="scope.value"
                            :model-value="scope.value"
                            :hint="[$t('Please.enter.your.reference')]"
                          >
                            <template v-slot:after>
                              <q-btn
                                flat
                                dense
                                color="negative"
                                icon="cancel"
                                @click.stop.prevent="scope.cancel"
                              />

                              <q-btn
                                flat
                                dense
                                color="positive"
                                icon="check_circle"
                                @click.stop.prevent="scope.set"
                              />
                            </template>
                          </q-input>
                        </q-popup-edit>
                      </div>
                    </td>
                  </tr>
                  <tr style="background-color: #fafafa">
                    <td style="text-align: center" class="table-items">
                      <p class="table-header">{{ $t("quote") }}</p>
                    </td>
                    <td>
                      <p class="summary-text">
                        {{ $t("Official.Fees") }}:
                        <span class="fee">{{ this.officialfeeTotal }} €</span>
                      </p>
                      <ul style="list-style: none">
                        <li>
                          <p class="summary-sub-item"> {{this.fee.officialFee[0].name}}:
                            <span class="summary-span"> {{this.fee.officialFee[0].amount}} ₺</span>
                            <span class="fee currency"
                                  v-if="this.fee.officialFee[1] === undefined"
                                  style="font-size: 14px">(EUR/TRY= {{this.epoData.fee.currency.eur}})</span>
                          </p>
                        </li>
                        <li v-if="this.fee.officialFee[1] !=undefined">
                          <p class="summary-sub-item"> {{this.fee.officialFee[1].name}}:
                            <span class="summary-span"> {{this.fee.officialFee[1].amount}} ₺</span>
                            <span class="fee currency" style="font-size: 14px">(EUR/TRY= {{this.epoData.fee.currency.eur}})</span>
                          </p>
                        </li>
                      </ul>
                      <hr />
                      <p class="summary-text">
                        {{ $t("Service.Fees") }}:
                        <span class="fee"
                        >{{ this.serviceFeeTotal }} €</span
                        >
                      </p>
                      <ul style="list-style: none">
                        <li v-for="(serviceItem,serviceIndex) in this.fee.serviceFee">
                          <p class="summary-sub-item">
                            {{ serviceItem.item }}:
                            <span class="summary-span"
                            >{{ serviceItem.amount }} €</span
                            >
                          </p>
                        </li>
                      </ul>
                      <hr />
                      <p class="summary-text">
                        {{ $t("Translation.Fees") }}:
                        <span class="fee">
                            {{
                            this.fee.translationFee.fullTextCount *
                            this.fee.translation100keywordFee
                          }}
                            €
                          </span>
                      </p>
                      <ul style="list-style: none">
                        <li>
                          <p class="summary-sub-item">
                            {{ $t("Estimated.words.in.claims.description.and.figures") }}:
                            <span>{{
                                this.fee.translationFee.fullTextCount
                              }}</span>
                          </p>
                        </li>
                        <li>
                          <p class="summary-sub-item">
                            {{ $t("Trans.fees") }}:
                            <span>
                                {{ this.translationfeeTotal }} ({{
                                this.fee.translation100keywordFee
                              }}€/{{ $t("word") }})</span
                            >
                          </p>
                        </li>
                      </ul>
                      <hr />
                      <p class="summary-text">
                        {{ $t("total.payable") }}:
                        <span class="fee">
                            {{ parseFloat(this.totalPayable).toFixed(2) }} €
                          </span>
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td style="text-align: center" class="table-items">
                      <p class="table-header">{{ $t("contact.info") }}</p>
                    </td>
                    <td>
                      <p class="summary-text">
                        {{ $t("user.name") }}:
                        <span>{{ this.name }}</span>
                      </p>
                      <p class="summary-text">
                        {{ $t("user.email") }}:
                        <span>{{ this.email }}</span>
                      </p>
                      <p class="summary-text">
                        {{ $t("user.phone") }}:
                        <span>{{ this.phone }}</span>
                      </p>
                    </td>
                  </tr>
                  </tbody>
                </table>
                <!-- Case 3# Close To Expire -->
              <div v-if="
                  this.epoData.response.bibliographicData.status == 'close-to-expire'
                ">
                <p class="text-primary">
                <span class="text-dark"><strong>{{ $t("ep.close-to-expire") }}</strong></span
                >
                </p>
              </div>
              <!-- Case 3# End -->
              </div>
              <q-dialog v-model="card">
                <q-card>
                  <q-toolbar class="justify-center">
                    <q-avatar style="height: 100%; width: 100%">
                      <img
                        style="width: 200px; height: 200px"
                        src="https://png.pngtree.com/png-vector/20190228/ourmid/pngtree-check-mark-icon-design-template-vector-isolated-png-image_711429.jpg"
                      />
                    </q-avatar>
                  </q-toolbar>

                  <q-card-section>
                    {{ $t("successful.order") }}
                  </q-card-section>

                  <q-card-actions align="right" class="text-primary">
                    <q-btn flat :label="[$t('ok')]" @click="card = false" />
                  </q-card-actions>
                </q-card>
              </q-dialog>
              <q-stepper-navigation>
                <q-btn
                  v-if="step > 0"
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('back')]"
                  class="q-ml-sm"
                />
                <q-btn
                  @click="GetData()"
                  color="primary"
                  :label="[$t('save.the.quote')]"
                />
              </q-stepper-navigation>
            </q-step>
            <!-- Banner -->
            <template v-slot:message>
              <q-banner v-if="step === 1" class="bg-primary text-white q-px-lg">
                {{ $t("ep.step.1.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 2"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("ep.step.2.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 3"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("enter.agent.info") }}
              </q-banner>
              <q-banner
                v-else-if="step === 4"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("enter.company.info") }}
              </q-banner>
              <q-banner
                v-else-if="step === 5"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("ep.step.5.title") }}
              </q-banner>
              <q-banner v-else class="bg-primary text-white q-px-lg">
                {{ $t("response") }}
              </q-banner>
            </template>
          </q-stepper>
        </div>
      </div>
    </div>
  </q-page>
  <div v-if="fixed === true">
    <feedback
      :ipservice="title"
      :step="step"
      :EPpatentNumber="patentNumber"
      :EPBibliographicdata="this.epoData"
      :customerName="this.name"
      :customerPhone="this.phone"
      :customerEmail="this.email"
      :totalPayabale="this.totalPayable"
      :OfficialFee="this.officialfeeTotal"
      :ServiceFee="this.serviceFeeTotal"
      :TranslationFee="this.translationfeeTotal"
      @modal="GetValue"
    />
  </div>
  <q-dialog v-model="guide">
    <q-card>
      <q-card-section>
        <div class="text-h6">
          {{ $t("ep.validation.steps") }}
          <q-btn
            icon="close"
            class="float-right"
            flat
            round
            dense
            v-close-popup
          />
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section style="max-height: 50vh" class="scroll">
        <p>{{ $t("ep.validation.guides") }}</p>
      </q-card-section>

      <q-separator />
    </q-card>
  </q-dialog>
</template>

<script>
import {ref, defineAsyncComponent, getCurrentInstance} from "vue";
import { api } from "boot/axios";
import { useQuasar } from "quasar";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useMeta } from "quasar";

const metaData = {
  title: "European Patent Validation in Türkiye",
  titleTemplate: (title) => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: "canonical",
      href: "https://patent.istanbul/european-patent-validation-in-turkiye",
    },
  ],
};
export default {
  components: {
    Feedback: defineAsyncComponent(() => import("pages/feedback")),
  },

  methods: {
    getcompanyName(value) {
      this.companyName = value;
    },
    getTaxNumber(value) {
      this.taxNumber = value;
    },
    getCountryName(value) {
      this.countryName = value;
    },
    getStateName(value) {
      this.stateName = value;
    },
    getCityName(value) {
      this.cityName = value;
    },
    getAddressName(value) {
      this.addressName = value;
    },
    getPostCode(value) {
      this.postCode = value;
    },

    GetValue(value) {
      if (value === false) {
        this.fixed = false;
      }
    },
    isValidEmail(val) {
      const emailPattern =
        /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
      return emailPattern.test(val) || $t("invalid.email");
    },

    // Company Info
    companyInfo(arr) {
      this.companyInfoData = arr;
      this.step++;
    },

    backStep(val) {
      if (val) {
        this.step--;
      }
    },
  },
  mounted() {
    // console.log(this.$i18n.locale)
  },
  setup() {
    useMeta(metaData);
    const { t } = useI18n({ useScope: "global" });
    const $q = useQuasar();
    const loading = ref(false);
    const companyName = ref("");
    const taxNumber = ref("");
    const countryName = ref("");
    const stateName = ref("");
    const cityName = ref("");
    const addressName = ref("");
    const postCode = ref("");
    const router = useRouter();
    const instance = getCurrentInstance();
    const i18nLocale = instance

    function GetData() {
      console.log(instance)

      let orderlink = (Math.random() + 1).toString(36).substring(2);
      let ref = this.reference;
      if (ref === "") {
        ref = "---";
      }
      let official_fee_extension="";
      let late_service_fee ="";
      let serviceFee ="";
      if(this.fee.officialFee[1] !=undefined){
        official_fee_extension = this.fee.officialFee[1].amount
        late_service_fee = this.fee.serviceFee[0].amount
      }

      if(this.fee.serviceFee[1] ==undefined){
        serviceFee =  this.fee.serviceFee[0]['amount']
      }
      else{
        serviceFee = this.fee.serviceFee[1]['amount']
      }

      var currentLanguage = "";
      // Array of languages to compare
      const languages = ['de', 'es', 'fr', 'it', 'ja', 'ko', 'tr'];

        // Get the current URL
      const currentURL = window.location.href;

      // Find the matching language
      const foundLanguage = languages.find(language => currentURL.includes(language));

      // Return the found language if it exists
      if (foundLanguage) {
        currentLanguage = foundLanguage;
      } else {
        currentLanguage = "en"
      }
      api
        .post("/api/v1/order", {
          application_number: this.patentNumber,
          publication_date: this.epoData.response.bibliographicData.publicationDate,
          invention_title: this.epoData.response.bibliographicData.inventionTitle,
          user_name: this.name,
          user_phone: this.phone,
          user_email: this.email,
          service: "EP Validation",
          service_fee: this.serviceFeeTotal,
          official_fee: this.officialfeeTotal,
          ep_validation_official_fee:this.fee.officialFee[0].amount,
          official_fee_extension: official_fee_extension,
          late_service_fee: late_service_fee,
          ep_validation_service_fee:serviceFee,
          translation_quantity: this.fee.translationFee.fullTextCount,
          translation_fee: this.translationfeeTotal,
          translation_fee_per_word : this.fee.translation100keywordFee,
          total: this.totalPayable,
          link: orderlink,
          reference: ref,
        })
        .then((res) => {
          api.post("/api/v1/send-mail", {
              type: "EP Validation",
              name: this.name,
              email: this.email,
              patentNumber: this.patentNumber,
              link: orderlink,
              language: currentLanguage,
            })
            .then(() => {
              router.push({ path: `${t('order')}/` + orderlink });
            });
        });
    }

    function patentQuery(patentNumber) {
      let patentType = patentNumber.slice(-2);
      let checkURGENT = patentNumber.slice(-3);
      if (patentType == "B1" || checkURGENT == "URG") {
        api.post("/api/v1/query/" + patentNumber)
          .then((res) => {

            console.log( res.data.response.warning)
            if (res.data.status == false) {
              var dialogInfo = {
                title: "",
                message: "",
              };
              if (res.data.code === 404) {
                dialogInfo = {
                  title: t("ep.step.1.error.patentNumber.title"),
                  message: t("ep.step.1.error.patentNumber.description"),
                };
              } else if (res.data.code === 410) {
                dialogInfo = {
                  title: t("ep.step.1.error.serverError.title"),
                  message: t("ep.step.1.error.serverError.description"),
                };
              } else if (res.data.code === 411) {
                dialogInfo = {
                  title: t("ep.step.1.error.missingFee.title"),
                  message: t("ep.step.1.error.missingFee.description"),
                };
              } else {
                dialogInfo = {
                  title: t("ep.step.1.error.serverError.title"),
                  message: t("ep.step.1.error.serverError.description"),
                };
              }
              this.loading = false;
              $q.dialog({
                title: dialogInfo.title,
                icon: "done",
                type: "negative",
                message: dialogInfo.message,
                color: "negative",
              });
            } else {
              this.epoData = res.data;
              this.step++;
              this.fee["translationFee"] = res.data.fee.translationFee;
              this.fee["translation100keywordFee"] =
                res.data.fee.translation100keywordFee / 100;
              this.fee["serviceFee"] = res.data.fee.serviceFee;
              this.fee["officialFee"] = res.data.fee.officialFee;
              this.fee["eur"] = res.data.fee.currency.eur;

              let totalServiceFeeAmount = 0;
              let totalOfficialFeeAmount = 0;
              let totalServiceFee = res.data.fee.serviceFee.forEach((item) => {
                totalServiceFeeAmount += parseInt(item.amount);
              });
              let totalOfficialFee = res.data.fee.officialFee.forEach(
                (item) => {
                  totalOfficialFeeAmount += parseInt(item.amount);
                }
              );

              this.fee["total"] =
                parseInt(totalOfficialFeeAmount / this.fee.eur) +
                parseInt(totalServiceFeeAmount) +
                parseInt(this.fee.translationFee.amount);

              this.officialfeeTotal = parseFloat(
                totalOfficialFeeAmount / this.epoData.fee.currency.eur
              ).toFixed(2);
              this.serviceFeeTotal = parseFloat(totalServiceFeeAmount).toFixed(
                2
              );
              this.translationfeeTotal =
                this.fee.translationFee.fullTextCount *
                this.fee.translation100keywordFee;

              this.totalPayable =
                parseFloat(this.officialfeeTotal) +
                parseFloat(this.serviceFeeTotal) +
                parseFloat(this.translationfeeTotal);
            }
            this.loading = false;
          })
          .catch((err) => {
            this.loading = false;
            $q.dialog({
              title: t("ep.step.1.error.serverError.title"),
              icon: "error",
              type: "negative",
              message: t("ep.step.1.error.serverError.description"),
              color: "negative",
            });
          });
      } else {
        this.loading = false;
        $q.dialog({
          title: t("ep.step.1.error.patentNumberB1.title"),
          icon: "error",
          type: "negative",
          message: t("ep.step.1.error.patentNumberB1.description"),
          color: "negative",
        });
      }
    }

    function stepperNext() {
      if (this.step < 6) {
        this.step++;
      }
      this.virtualStep++;
    }
    function stepperPrevious() {
      this.step--;
    }

    function finishEPValidation() {
      $q.dialog({
        icon: "done",
        type: "negative",
        message:
          "<div class='text-center'><i class='q-icon notranslate material-icons' style='font-size: 62px;color:green'>done</i><br><br>" +
          t("ep.validation.finish") +
          "<br><strong>patent.istanbul</strong></div>",
        color: "negative",
        html: true,
      });
    }
    return {
      i18nLocale,
      GetData,
      stateName,
      cityName,
      addressName,
      postCode,
      countryName,
      taxNumber,
      companyName,
      finishEPValidation,
      guide: ref(null),
      loading,
      step: ref(1),
      virtualStep: ref(1),
      patentNumber: ref(""),
      fee: ref([]),
      dense: ref(false),
      officialfeeTotal: ref([]),
      serviceFeeTotal: ref([]),
      translationfeeTotal: ref([]),
      totalPayable: ref([]),
      epoData: ref(null),
      steps: ref([false, false, false, false, false, false]),
      patentQuery,
      stepperNext,
      stepperPrevious,
      name: ref(""),
      email: ref(""),
      phone: ref(""),
      fixed: ref(false),
      title: ref("EP VALIDATION"),
      companyInfoData: ref([]),
      relationship: ref("Service Relationship"),
      note: ref(""),
      reference: ref(""),
      step1Submit() {
        this.loading = true;
        this.patentQuery(this.patentNumber);
      },
      step3Submit() {
        this.step++;
      },
      step4Submit() {
        this.step++;
      },
    };
  },
};
</script>
<style>
.demo {
  border: 0px solid;
  border-collapse: collapse;
  margin-bottom: 1rem;
}
.demo td {
  border: 1px solid #c0c0c0;
  /*padding:20px;*/
  padding: 10px 15px;
  font-weight: 500;
  letter-spacing: 0.5px;
  background-color: #d1d1d11c;
}
* {
  font-family: "EB Garamond", serif;
}

p.table-header {
  font-size: 16px;
}
p.table-items {
  font-size: 16px;
}
span.summary-span,
p.summary-sub-item {
  font-size: 16px;
  line-height: 15px;
}

span.summary-span{
  float: none;
}

.reference input {
  margin-top: 25px;
}

.fee {
  float: right;
}

@media screen and (max-width: 650px) {
  .summary-step {
    padding: 0 !important;
  }

  ul {
    display: inline-block;
    padding: 0;
  }
  .fee {
    float: none;
  }
  span.summary-span,
  p.summary-sub-item {
    font-size: 14px;
    line-height: 20px;
    display: grid;
  }
  .currency {
    margin-top: 15px;
    display: block;
  }
}
</style>
