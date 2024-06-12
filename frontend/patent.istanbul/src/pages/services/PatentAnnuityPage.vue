<template>
  <q-page class="main-container">
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-sm-12 col-xs-12 q-mx-auto">
          <h1 class="text-h2 text-center text-primary page-header">
            {{ $t("annuity.title") }}
            <q-btn
              flat
              round
              icon="info"
              @click="guide = true"
              :title="[$t('ep.validation.steps')]"
            />
          </h1>
          <p class="text-center service-description">
            {{ $t("annuity.description") }}
          </p>
          <button
            class="float-right feedback-btn feedback-txt"
            @click="fixed = true"
          >
            {{ $t("feedback") }}
          </button>
          <br /><br />
          <q-stepper
            header-nav
            v-model="step"
            ref="stepper"
            color="primary"
            animated
            vertical
            class="q-ma-md"
          >
            <!-- Step 1 -->
            <q-step
              :name="1"
              :title="$t('annuity.step.1.description')"
              :done="step > 1"
              :header-nav="virtualStep > 0"
            >
              <div class="q-pa-md">
                <q-form @submit="step1Submit()">
                  <q-input
                    v-model="patentNumber"
                    filled
                    type="text"
                    :label="[$t('annuity.step.1.input.label')]"
                    :hint="[$t('annuity.step.1.input.hint')]"
                    mask="####/#####"
                    class="q-mb-md"
                    lazy-rules
                    :rules="[
                      (val) =>
                        (val && val.length > 9) ||
                        $t('annuity.step.1.input.error'),
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
                      :label="[$t('annuity.step.1.button.text')]"
                    />
                  </q-stepper-navigation>
                </q-form>
              </div>
            </q-step>
            <!-- Step 2 -->
            <q-step
              :name="2"
              :title="$t('annuity.step.2.description')"
              :done="step > 2"
              :header-nav="virtualStep > 1"
            >
              <p style="margin-top: 20px">
                {{ $t("annuity.step.2.label.applicationNumber") }} :
                <strong>{{
                    this.patentData.basvuruBilgileri.basvuruNumarasi
                  }}</strong>
              </p>
              <p
                v-if="
                  typeof this.patentData.basvuruBilgileri.tescilTarihi !==
                  'undefined'
                "
              >
                {{ $t("registration.date") }}
                <strong>
                  {{ this.patentData.basvuruBilgileri.tescilTarihi }}
                </strong>
              </p>
              <p v-else>
                {{ $t("application.date") }}
                <strong>
                  {{ this.patentData.basvuruBilgileri.basvuruTarihi }}
                </strong>
              </p>
              <p>
                {{ $t("annuity.step.2.label.inventionName") }} :
                <strong>{{ this.patentData.bulus.bulusAdi }}</strong>
              </p>
              <p>
                {{ $t("annuity.step.2.label.applicantName") }} :
                <strong>{{ this.patentData.applicant.name }}</strong>
              </p>
              <p v-if="this.patentData.applicant.address != ''">
                {{ $t("annuity.step.2.label.applicantAddress") }} :
                <strong>{{ this.patentData.applicant.address }}</strong>
              </p>
              <div v-if="this.patentData.odemeTarihleri.length != 0">
                <p>{{ $t("annuity.step.2.label.paymentDates") }} :</p>
                <ul>
                  <li
                    v-for="item in this.patentData.odemeTarihleri"
                    :key="item.index"
                  >
                    {{ $t("annuity.step.2.label.year") }} :
                    <strong>{{ item.paymentDate }}</strong
                    ><span v-if="item.paymentAmount != ''">
                      - {{ $t("annuity.step.2.label.fee") }} :
                      <strong>{{ item.paymentAmount }}</strong></span
                  >
                  </li>
                </ul>
              </div>
              <q-stepper-navigation>
                <q-btn
                  @click="stepperNext()"
                  color="primary"
                  :label="step === 3 ? 'Finish' : 'Pay'"
                />
                <q-btn
                  v-if="step > 0"
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('annuity.step.2.button.prev')]"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <!-- Step 3 -->
            <q-step
              :name="3"
              :title="$t('annuity.step.3.description')"
              :done="step > 3"
              :header-nav="virtualStep > 2"
            >
              <div v-if="!this.patentData.patentRenewalStatus.status">
                <p style="margin-top: 20px">
                  {{ $t("patent.renewal.status") }}
                  <strong>{{
                      this.patentData.patentRenewalStatus.message
                    }}</strong>
                  {{ $t("please.contact") }}
                </p>
                <q-btn
                  color="primary"
                  label="Contact us"
                  class="q-ma-md"
                  @click="this.inception = true"
                />
                <div>
                  <q-dialog v-model="inception">
                    <q-card style="width: 500px; max-width: 80vw">
                      <ContactPage
                        :service="'PATENT ANNUITY'"
                        :patentNumber="patentNumber"
                      ></ContactPage>
                    </q-card>
                  </q-dialog>
                </div>
              </div>
              <div v-else>
                <p style="margin-top: 20px">
                  <strong>{{
                      this.patentData.patentRenewalStatus.message
                    }}</strong>
                </p>
                <p>
                  {{ $t("service.fee") }} :
                  <strong>{{
                      this.patentData.fee.serviceFee.amount +
                      " " +
                      this.patentData.fee.serviceFee.currency
                    }}</strong>
                </p>
                <p>
                  {{ $t("renewal.fee") }} :
                  <strong
                  >{{ this.patentData.fee.officialFee.amount }} TRY</strong
                  >
                </p>
                <p v-if="this.patentData.patentRenewalStatus.lateFee">
                  {{ $t("annuity.step.3.label.lateFee") }} :
                  <strong
                  >{{
                      new Intl.NumberFormat("tr-TR", {
                        currency: "TRY",
                      }).format(parseInt(this.lateFee))
                    }}
                    {{ $t("try") }}</strong
                  >
                </p>
                <p>
                  {{ $t("eur") }} :
                  <strong>{{ this.patentData.fee.currency.eur }} TRY</strong>
                </p>
                <p>
                  {{ $t("total") }} :
                  <strong>{{
                      new Intl.NumberFormat("de-DE", {
                        style: "currency",
                        currency: "EUR",
                      }).format(
                        parseInt(this.patentData.fee.officialFee.amount) /
                        parseInt(this.patentData.fee.currency.eur) +
                        parseInt(this.patentData.fee.serviceFee.amount) +
                        parseInt(
                          this.lateFee / this.patentData.fee.currency.eur
                        )
                      )
                    }}</strong>
                </p>
              </div>
              <q-stepper-navigation>
                <q-btn
                  @click="stepperNext()"
                  color="primary"
                  :label="[$t('annuity.step.3.button.next')]"
                  v-if="this.patentData.patentRenewalStatus.status"
                />
                <q-btn
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('annuity.step.3.button.prev')]"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="4"
              :title="$t('annuity.step.4.description')"
              :done="step > 4"
              :header-nav="virtualStep > 3"
            >
              <q-form @submit="step4Submit()">
                <div class="row">
                  <div class="col-md-4 col-sm-12 col-xs-12 q-pa-md">
                    <q-input
                      filled
                      v-model="name"
                      :label="[$t('annuity.step.4.input.name.label')]"
                      :hint="[$t('annuity.step.4.input.name.hint')]"
                      lazy-rules
                      :rules="[
                        (val) =>
                          (val && val.length > 0) ||
                          $t('annuity.step.4.input.name.error'),
                      ]"
                    />
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-12 q-pa-md">
                    <q-input
                      filled
                      v-model="phone"
                      :label="[$t('annuity.step.4.input.phone.label')]"
                      :hint="[$t('annuity.step.4.input.phone.hint')]"
                      maxlength="20"
                      :rules="[
                        (val) =>
                          (val && val.length > 4) ||
                          $t('annuity.step.4.input.phone.error'),
                      ]"
                    />
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-12 q-pa-md">
                    <q-input
                      filled
                      v-model="email"
                      :label="[$t('annuity.step.4.input.email.label')]"
                      :hint="[$t('annuity.step.4.input.email.hint')]"
                      lazy-rules
                      :rules="[
                        (val) =>
                          !!val || $t('annuity.step.4.input.email.error'),
                        isValidEmail,
                      ]"
                    />
                  </div>
                </div>
                <q-stepper-navigation>
                  <q-btn
                    type="submit"
                    color="primary"
                    :label="[$t('annuity.step.4.button.next')]"
                    @click="stepperNext()"
                  />
                  <q-btn
                    flat
                    color="primary"
                    @click="stepperPrevious()"
                    :label="[$t('annuity.step.4.button.prev')]"
                    class="q-ml-sm"
                  />
                </q-stepper-navigation>
              </q-form>
            </q-step>

            <!-- Step 4 End -->
            <!-- Step 5 -->
            <q-step
              :name="5"
              :title="[$t('app.summary')]"
              :done="step > 5"
              :header-nav="virtualStep > 4"
            >
              <div class="col-md-12 result justify-center" style="text-align: -webkit-center">
                <table class="demo">
                  <tbody>
                  <tr>
                    <td style="text-align:center">
                      <p class="table-header">
                        {{ $t("case.info") }}
                      </p>
                    </td>
                    <td style="width: 80%">
                      <p class="table-items">{{ this.fee.serviceFee.item }}</p>
                      <p class="summary-text">{{ $t("app.number") }} <span class="summary-span">{{ this.patentNumber }}</span></p>
                      <p class="summary-text">{{ $t("Title") }}: <span class="summary-span">{{this.patentData.bulus.bulusAdi}}</span></p>
                      <p class="summary-text">{{ $t("registration.date") }}: <span class="summary-span">{{this.patentData.basvuruBilgileri.tescilTarihi}}</span></p>
                      <p class="summary-text">
                      </p>
                      <span style="font-size: 16px;font-weight: bold" class="summary-text">{{ $t("your.ref") }}: </span>
                      <div class="cursor-pointer" style="display: inline-block">
                        <span style="border-bottom: 1px dotted blue;text-decoration: none;font-size: 16px" v-if="this.reference !=''">
                          {{reference}}
                        </span>
                        <span style="border-bottom: 1px dotted blue;text-decoration: none;font-size: 16px" v-else>
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
                                flat dense color="negative" icon="cancel"
                                @click.stop.prevent="scope.cancel"
                              />

                              <q-btn
                                flat dense color="positive" icon="check_circle"
                                @click.stop.prevent="scope.set"
                              />
                            </template>
                          </q-input>
                        </q-popup-edit>
                      </div>
                    </td>
                  </tr>
                  <tr style="background-color:#fafafa">
                    <td style="text-align:center" class="table-items">
                      <p class="table-header">{{ $t("quote") }}</p></td>
                    <td>
                      <p class="summary-text"> {{ $t("Official.Fees") }}:
                        <span class="fee">{{parseFloat(total_official_Fee).toFixed(2)}} €</span>
                      </p>
                      <ul style="list-style: none">
                        <li>
                          <p class="summary-sub-item">{{ $t("Patent.Annuity.official.fees") }}:
                            <span class="summary-span">{{this.patentData.fee.officialFee.amount}} ₺</span>
                            <span class="fee currency" style="font-size: 14px;float: right" v-if="this.lateFee === 0">(EUR/TRY= {{this.patentData.fee.currency.eur}})</span>
                          </p>
                        </li>
                        <li v-if="this.lateFee > 0">
                          <p class="summary-sub-item">{{ $t("Official.Late.Fee") }}:
                            <span class="summary-span">{{this.lateFee}} ₺</span>
                            <span class="fee currency" style="font-size: 14px">(EUR/TRY= {{this.patentData.fee.currency.eur}})</span>
                          </p>
                        </li>
                      </ul>
                      <hr>
                      <p class="summary-text"> {{ $t("Service.Fees") }}:
                        <span class="fee">{{this.patentData.fee.serviceFee.amount}} €</span>
                      </p>
                      <ul style="list-style: none">
                        <li>
                          <p class="summary-sub-item">{{ $t("Patent.Annuity.service.fees") }}:
                            <span class="summary-span">{{this.patentData.fee.serviceFee.amount}} €</span>
                          </p>
                        </li>
                      </ul>
                      <hr>
                      <p class="summary-text"> {{ $t("total.payable") }}:
                        <span class="fee"> {{parseFloat(this.total_payable).toFixed(2) }} €</span>
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td style="text-align:center" class="table-items">
                      <p class="table-header">{{ $t("contact.info") }}</p>
                    </td>
                    <td>
                      <p class="summary-text"> {{ $t("user.name") }}:
                        <span>{{this.name}}</span>
                      </p>
                      <p class="summary-text"> {{ $t("user.email") }}:
                        <span>{{this.email}}</span>
                      </p>
                      <p class="summary-text"> {{ $t("user.phone") }}:
                        <span>{{this.phone}}</span>
                      </p>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <q-separator />
              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <h5 class="h5 text-primary q-my-md">{{ $t("note") }}</h5>
                  <q-input
                    filled
                    v-model="note"
                    clearable
                    type="textarea"
                    :label="[$t('note')]"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn
                  color="primary"
                  @click="finishEPValidation"
                  :label="[$t('finish')]"
                />
                <q-btn
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('back')]"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <!-- Step 5 End -->

            <!-- Banner -->
            <template v-slot:message>
              <q-banner v-if="step === 1" class="bg-primary text-white q-px-lg">
                {{ $t("annuity.step.1.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 2"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("annuity.step.2.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 3"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("annuity.step.3.description") }}
              </q-banner>
              <q-banner v-else class="bg-primary text-white q-px-lg">
                {{ $t("annuity.step.4.description") }}
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
      :patentAnnuityNumber="this.patentNumber"
      :registrationDate ="this.RegistrationDate"
      :inventionName="this.InventionName"
      :applicantName="this.ApplicantName"
      :applicantAddress="this.ApplicantAddress"
      :paymentDates="this.PaymentDates"
      :ServiceFee="this.ServiceFee"
      :renewalFee="this.RenewalFee"
      :exchangeRate="this.exchange_rate"
      :totalPayabale="this.total_payable"
      :customerName="this.name"
      :customerEmail="this.email"
      :customerPhone="this.phone"
      :reference="this.reference"
      :OfficialFee="total_official_Fee"
      @modal="GetValue"

    />
  </div>
  <q-dialog v-model="guide">
    <q-card>
      <q-card-section>
        <div class="text-h6">
          {{ $t("annuity.steps") }}
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
        <p>{{ $t("annuity.guides") }}</p>
      </q-card-section>

      <q-separator />
    </q-card>
  </q-dialog>
</template>

<script>
import { defineAsyncComponent, ref } from "vue";
import { api } from "boot/axios";
import { useQuasar } from "quasar";
import { useI18n } from "vue-i18n";
import { useMeta } from "quasar";
import { useRouter } from "vue-router";

const metaData = {
  title: "Patent Renewal In Türkiye",
  titleTemplate: (title) => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: "canonical",
      href: "https://patent.istanbul/patent-renewals-in-turkiye",
    }
  ],
};
export default {
  components: {
    Feedback: defineAsyncComponent(() => import("pages/feedback")),
    ContactPage: defineAsyncComponent(() => import("pages/ContactPage.vue")),
  },

  methods: {
    finishEPValidation() {
      let orderlink = (Math.random() + 1).toString(36).substring(2);
      let ref = "";
      if (this.reference == ""){
        ref = "---";
      }
      else{
        ref = this.reference
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
        .post("/api/v1/order/patentannuity", {
          application_number: this.patentNumber,
          service:this.fee.serviceFee.item,
          publication_date: this.patentData.basvuruBilgileri.tescilTarihi,
          invention_title: this.patentData.bulus.bulusAdi,
          user_name: this.name,
          user_phone: this.phone,
          user_email: this.email,
          service_fee: this.patentData.fee.serviceFee.amount,
          official_fee: this.total_official_Fee,
          patent_annuity_official_fee: this.patentData.fee.officialFee.amount,
          total: this.total_payable,
          late_official_fee:this.lateFee,
          note:this.note,
          link: orderlink,
          reference: ref
        }).then((res) => {
          api
            .post("/api/v1/send-mail", {
              type: "Patent Annuity",
              name: this.name,
              email: this.email,
              patentNumber: this.patentNumber,
              link: orderlink,
              language: currentLanguage,
            })
            .then(() => {
              this.$router.push({ path: `${t('order')}/` + orderlink });
            });
        });
      },
    GetValue(value) {
      if (value === false) {
        this.fixed = false;
      }
    },
    isValidEmail(val) {
      const emailPattern =
        /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
      return emailPattern.test(val) || "Invalid email";
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
  setup() {
    useMeta(metaData);
    const $q = useQuasar();
    const loading = ref(false);
    const isRenewable = ref(false);
    const { t } = useI18n({ useScope: "global" });
    const router = useRouter();

    function patentQuery(patentNumber) {
      patentNumber = patentNumber.replace("/", "-");
      api
        .post("/api/v1/query/" + patentNumber + "/tr")
        .then((res) => {
          if (res.data.status == false) {
            var dialogInfo = {
              title: "",
              message: "",
            };
            if (res.data.code === 404) {
              dialogInfo = {
                title: t("annuity.step.1.error.patentNumber.title"),
                message: t("annuity.step.1.error.patentNumber.description"),
              };
            } else if (res.data.code === 410) {
              dialogInfo = {
                title: t("annuity.step.1.error.serverError.title"),
                message: t("annuity.step.1.error.serverError.description"),
              };
            } else if (res.data.code === 411) {
              dialogInfo = {
                title: t("annuity.step.1.error.missingFee.title"),
                message: t("annuity.step.1.error.missingFee.description"),
              };
            } else {
              dialogInfo = {
                title: t("annuity.step.1.error.serverError.title"),
                message: t("annuity.step.1.error.serverError.description"),
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
            this.patentData = res.data;
            if (this.patentData.patentRenewalStatus.lateFee == true) {
              this.lateFee =
                parseInt(this.patentData.fee.officialFee.amount) * 1.18 * 0.25;
              this.fee["lateFee"] = this.lateFee;
            }
            this.loading = false;

            this.step++;
            this.fee["officialFee"] = this.patentData.fee.officialFee;
            this.fee["serviceFee"] = this.patentData.fee.serviceFee;
            this.fee["eur"] = this.patentData.fee.currency.eur;
          }
          this.official_fee = parseFloat(this.patentData.fee.officialFee.amount) + parseFloat(this.lateFee);
          this.total_official_Fee = this.official_fee / this.patentData.fee.currency.eur;
          this.total_payable = this.total_official_Fee + parseFloat(this.patentData.fee.serviceFee.amount);
          this.ApplicantName = this.patentData.applicant.name;
          this.RegistrationDate = this.patentData.basvuruBilgileri.tescilTarihi;
          this.InventionName = this.patentData.bulus.bulusAdi
          this.ApplicantAddress = this.patentData.applicant.address
          this.PaymentDates = this.patentData.odemeTarihleri
          this.RenewalFee = this.patentData.fee.officialFee.amount
          this.ServiceFee = this.patentData.fee.serviceFee.amount
          this.exchange_rate = this.patentData.fee.currency.eur
        })
        .catch((err) => {
          this.loading = false;
          $q.dialog({
            title: t("annuity.step.1.error.serverError.description"),
            message: t("annuity.step.1.error.serverError.description"),
            icon: "error",
            type: "negative",
            color: "negative",
          });
        });
    }

    function stepperNext() {
      this.step++;
      this.virtualStep++;
    }
    function stepperPrevious() {
      this.step--;
    }
    return {
      router,
      note:ref(""),
      guide: ref(null),
      companyInfoData: ref([]),
      separator: ref("vertical"),
      inception: ref(false),
      fixed: ref(false),
      title: ref("PATENT ANNUITY"),
      name: ref(""),
      email: ref(""),
      phone: ref(""),
      step4validation: ref(false),
      step: ref(1),
      virtualStep: ref(1),
      patentNumber: ref(""),
      patentData: ref(null),
      steps: ref([false, false, false, false, false, false]),
      patentQuery,
      stepperNext,
      stepperPrevious,
      loading,
      isRenewable,
      fee: ref([]),
      lateFee: ref(0),
      euro: ref(null),
      reference: ref(""),
      official_fee: ref(0),
      total_official_Fee: ref(0),
      total_payable:ref(0),
      ApplicationNumber:ref(""),
      RegistrationDate:ref(""),
      InventionName:ref(""),
      ApplicantName:ref(""),
      ApplicantAddress:ref(""),
      PaymentDates:ref([]),
      RenewalFee:ref(""),
      ServiceFee:ref(""),
      exchange_rate:ref(""),
      step1Submit() {
        this.loading = true;
        this.patentQuery(this.patentNumber);
      },
      step4Submit() {
        $q.dialog({
          icon: "done",
          type: "negative",
          message:
            "<div class='text-center'><i class='q-icon notranslate material-icons' style='font-size: 62px;color:green'>done</i><br><br>" +
            t("annuity.step.4.success") +
            "<br><strong>patent.istanbul</strong></div>",
          color: "negative",
          html: true,
        });
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

.reference input {
  margin-top: 25px;
}
span.summary-span {
  float: right;
}
span.summary-span {
  float: none;
}
</style>
