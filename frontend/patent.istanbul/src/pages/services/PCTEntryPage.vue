<template>
  <q-page class="main-container">
    <div class="container">
      <div class="row">
        <div class="col-md-10 q-mx-auto col-xs-12">
          <h1 class="text-h2 text-center text-primary page-header">
            {{ $t("pct.title") }}
            <q-btn
              flat
              round
              icon="info"
              @click="guide = true"
              :title="[$t('pct.steps')]"
            />
          </h1>
          <p class="text-center service-description">
            {{ $t("pct.description") }}
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
            <!-- Step 1 Publication Number -->
            <q-step :name="1" title="Publication Number" :done="step > 1">
              <div class="q-pa-md">
                <q-form @submit="step1Submit()">
                  <q-input
                    v-model="patentNumber"
                    filled
                    type="text"
                    :label="[$t('pct.step.1.input.label')]"
                    :hint="[$t('pct.step.1.input.hint')]"
                    class="q-mb-md"
                    mask="XXXXXX/XXXXXXXXXX"
                    lazy-rules
                    :rules="[
                      (val) =>
                        (val && val.length > 2) || $t('pct.step.1.input.error'),
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
                      :label="[$t('pct.step.1.button.text')]"
                    />
                  </q-stepper-navigation>
                </q-form>
              </div>
            </q-step>

            <!-- Step 2 Bibliographic Data -->
            <q-step
              :name="2"
              :title="[$t('pct.step.2.title')]"
              :done="step > 2"
              :header-nav="virtualStep > 1"
            >
              <q-form @submit="step2Submit()">
                <p style="margin-top: 20px">
                  {{ $t("pct.step.2.label.patentNumber") }} :
                  <strong>{{ this.epoData.number }}</strong>
                </p>
                <p>
                  {{ $t("pct.step.2.label.filingDate") }} :
                  <strong>{{
                      this.epoData.response.bibliographicData.publicationDate
                    }}</strong>
                </p>
                <p>
                  {{ $t("pct.step.2.label.applicationDate") }} :
                  <strong>{{
                      this.epoData.response.bibliographicData.applicationDate
                    }}</strong>
                </p>
                <p>
                  {{ $t("pct.step.2.label.inventionTitle") }} :
                  <strong>{{
                      this.epoData.response.bibliographicData.inventionTitle
                    }}</strong>
                </p>
                <p>
                  {{ $t("pct.step.2.label.applicant") }} :
                  <strong>{{
                      this.epoData.response.bibliographicData.applicant
                    }}</strong>
                </p>
                <q-separator class="q-ma-md" />
                <p>Is there a patent priority right?</p>
                <div class="q-gutter-sm">
                  <q-radio
                    v-model="priority"
                    val="yes"
                    :label="[$t('pct.step.2.radio.priority.yes')]"
                  />
                  <q-radio
                    v-model="priority"
                    val="no"
                    :label="[$t('pct.step.2.radio.priority.no')]"
                  />
                </div>
                <br />
                <div v-if="this.priority == 'yes'" class="q-gutter-sm">
                  <div class="row">
                    <div class="col-xs-12 col-md-3 q-pa-xs">
                      <q-input
                        v-model="priorityTitle"
                        type="text"
                        filled
                        :hint="[$t('pct.step.2.input.number.hint')]"
                      />
                    </div>
                    <div class="col-xs-12 col-md-3 q-pa-xs">
                      <q-input
                        v-model="priorityDate"
                        filled
                        type="date"
                        :hint="[$t('pct.step.2.input.date.hint')]"
                      />
                    </div>
                    <div class="col-xs-12 col-md-6 q-pa-xs">
                      <q-btn
                        color="primary"
                        @click="add(this.priorityTitle, this.priorityDate)"
                        style="
                          max-height: 40px;
                          min-width: 100px;
                          margin-left: 10px;
                          margin-top: 7px;
                        "
                      >
                        {{ $t("pct.step.2.button.add") }}
                      </q-btn>
                      <q-btn
                        color="primary"
                        flat
                        @click="this.priorities = []"
                        style="
                          max-height: 40px;
                          min-width: 100px;
                          margin-left: 10px;
                          margin-top: 7px;
                        "
                      >
                        {{ $t("pct.step.2.button.clear") }}
                      </q-btn>
                    </div>
                  </div>
                  <div v-if="this.priorities.length > 0">
                    <q-card class="q-py-md">
                      <q-card-section>
                        <div class="text-h6">
                          {{ $t("pct.step.2.label.priorities") }}
                        </div>
                      </q-card-section>
                      <q-list>
                        <q-item
                          v-ripple
                          v-for="(priority, index) in this.priorities"
                          :key="priority.index"
                        >
                          <q-item-section side top>
                            <q-checkbox
                              @click="this.priorities.splice(index, 1)"
                            />
                          </q-item-section>
                          <q-item-section
                          >{{
                              priority.date + " - " + priority.documentNumber
                            }}
                          </q-item-section>
                        </q-item>
                      </q-list>
                    </q-card>
                  </div>
                </div>

                <q-stepper-navigation>
                  <q-btn
                    v-if="step > 0"
                    flat
                    color="primary"
                    @click="stepperPrevious()"
                    :label="[$t('pct.step.2.button.prev')]"
                    class="q-ml-sm"
                  />
                  <q-btn
                    color="primary"
                    :label="[$t('pct.step.2.button.next')]"
                    type="submit"
                  />
                </q-stepper-navigation>
              </q-form>
            </q-step>

            <!-- Step 3 Summary -->
            <q-step
              :name="3"
              :title="[$t('pct.step.3.title')]"
              :done="step > 3"
              :header-nav="virtualStep > 2"
            >
              <div style="margin-top: 20px">
                <!--
                  Case #1 : Expired
                -->
                <div v-if="this.fee.status == 'expired'">
                  <p>
                    <strong>{{ $t("pct.expired") }}</strong>
                  </p>
                  <q-btn
                    color="primary"
                    :label="[$t('ep.contact.us')]"
                    class="q-ma-md"
                    @click="$router.replace('/contact')"
                  />
                </div>
                <!--
                  Case #2 : Very close to expire
                -->
                <div v-else-if="this.fee.status == 'very-close-to-expire'">
                  <p class="text-primary">
                    <span class="text-dark"
                    ><strong>{{
                        $t("pct.very-close-to-expire")
                      }}</strong></span
                    >
                  </p>
                  <q-btn
                    color="primary"
                    :label="[$t('ep.contact.us')]"
                    class="q-ma-md"
                    @click="$router.replace('/contact')"
                  />
                </div>
                <!--
                  Case #3 : Close to expire
                -->
                <div v-else-if="this.fee.status == 'close-to-expire'">
                  <p class="text-primary">
                    <span class="text-dark"
                    ><strong>{{ $t("pct.close-to-expire") }}</strong></span
                    >
                  </p>
                </div>
                <!--
                  Case #4 : Valid
                -->
                <div v-else-if="this.fee.status == 'valid'">
                  <p class="text-primary">
                    <span class="text-dark"
                    ><strong>{{ $t("pct.valid") }}</strong></span
                    >
                  </p>
                </div>
                <div v-else>
                  <span class="text-bold">Loading</span>&nbsp;&nbsp;<q-spinner-dots
                  color="primary"
                  size="2em"
                />
                </div>
              </div>

              <q-stepper-navigation
                v-if="
                  this.fee.status == 'valid' ||
                  this.fee.status == 'close-to-expire'
                "
              >
                <q-btn
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('pct.step.3.button.prev')]"
                  class="q-ml-sm"
                />
                <q-btn
                  color="primary"
                  :label="[$t('pct.step.3.button.next')]"
                  @click="stepperNext()"
                />
              </q-stepper-navigation>
            </q-step>

            <!-- Step 4 Contact Information -->
            <q-step
              :name="4"
              :title="[$t('pct.step.4.title')]"
              :done="step > 4"
              :header-nav="virtualStep > 3"
            >
              <q-form @submit="this.step++">
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
                      maxlength="15"
                      :hint="[$t('pct.step.4.input.phone.hint')]"
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
                    :label="[$t('pct.step.6.button.prev')]"
                    class="q-ml-sm"
                  />
                  <q-btn
                    type="submit"
                    color="primary"
                    :label="[$t('pct.step.6.button.next')]"
                  />
                </q-stepper-navigation>
              </q-form>
            </q-step>

            <!-- Step 5 Result -->
            <q-step
              :name="5"
              :title="[$t('pct.step.6.description')]"
              :done="step > 5"
              :header-nav="virtualStep > 4"
            >
              <div
                class="col-md-12 result justify-center"
                style="text-align: -webkit-center"
              >
                <table class="demo">
                  <tbody>
                  <tr>
                    <td style="text-align: center">
                      <p class="table-header">{{ $t("case.info") }}</p>
                    </td>
                    <td style="width: 80%">
                      <p class="summary-text">
                        {{ $t("app.number") }}
                        <span class="summary-span">
                            {{ this.patentNumber }}
                          </span>
                      </p>
                      <p class="summary-text">
                        {{ $t("Title") }}:
                        <span class="summary-span">
                            {{
                            this.epoData.response.bibliographicData
                              .inventionTitle
                          }}
                          </span>
                      </p>
                      <p class="summary-text">
                        {{ $t("publication.date") }}
                        <span class="summary-span">
                            {{
                            this.epoData.response.bibliographicData
                              .publicationDate
                          }}
                          </span>
                      </p>
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
                            hint="Please enter your reference"
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
                        <span style="float: right">
                            {{
                            parseFloat(this.fee.total.officialFee).toFixed(2)
                          }}
                            $</span
                        >
                      </p>
                      <ul style="list-style: none">
                        <li v-for="(value, index) in this.fee.officialFee">
                          <p class="summary-sub-item">
                            {{ value.item.item_en }}
                            <span class="summary-span">
                                {{ value.amount }} ₺
                              </span>
                          </p>
                        </li>
                        <li v-if="this.fee.priorityFee != 0">
                          <p class="summary-sub-item">
                            {{ $t("Priority.Fee") }} ({{ this.fee.priority.count }} X
                            {{ this.fee.priority.fee.amount }} ₺)
                            <span class="summary-span">
                                {{ this.fee.priority.fee.total }} ₺
                              </span>
                            <span
                              class="fee currency"
                              style="font-size: 14px; float: right"
                            >(USD/TRY= {{ dollar }})</span
                            >
                          </p>
                        </li>
                      </ul>
                      <hr />
                      <p class="summary-text">
                        {{ $t("Service.Fees") }}:
                        <span style="float: right">
                            {{
                            parseFloat(this.fee.total.serviceFee).toFixed(2)
                          }}
                            $</span
                        >
                      </p>
                      <ul
                        style="list-style: none"
                        v-for="(value, index) in this.fee.serviceFee"
                      >
                        <li>
                          <p class="summary-sub-item">
                            {{ value.item }}
                            <span class="summary-span">
                                {{ value.amount }}$</span
                            >
                          </p>
                        </li>
                      </ul>
                      <hr />
                      <p class="summary-text">
                        {{ $t("Translation.Fees") }}:
                        <span style="float: right">
                            {{ this.fee.translationFee.total }}$
                          </span>
                      </p>
                      <ul style="list-style: none">
                        <li>
                          <p class="summary-sub-item">
                            {{ $t("Estimated.words.in.claims.description.and.figures") }}
                            <span class="summary-span">
                                {{ this.fee.translationFee.wordCount }}
                              </span>
                          </p>
                        </li>
                        <li>
                          <p class="summary-sub-item">
                            {{ $t("Trans.fees") }} (
                            {{ this.fee.translationFee.amount }} $ / {{ $t("word") }})
                            <span class="summary-span">
                                {{ this.fee.translationFee.total }} $
                              </span>
                          </p>
                        </li>
                      </ul>
                      <hr />
                      <p class="summary-text">
                        {{ $t("total.payable") }}:
                        <span style="float: right">
                            {{ parseFloat(this.fee.total.total).toFixed(2) }} $
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
                <div v-if="this.fee.status=='close-to-expire'"><p><strong>{{ $t('pct.close-to-expire') }}</strong></p></div>
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
                    <q-btn flat label="Okey" @click="card = false" />
                  </q-card-actions>
                </q-card>
              </q-dialog>

              <q-stepper-navigation>
                <q-btn
                  v-if="step > 0"
                  flat
                  color="primary"
                  @click="stepperPrevious()"
                  :label="[$t('pct.step.6.button.prev')]"
                  class="q-ml-sm"
                />
                <q-btn
                  @click="finishPCTEntry"
                  color="primary"
                  label="sAVE THE QUOTE"
                />
              </q-stepper-navigation>
            </q-step>

            <!-- Banner -->
            <template v-slot:message>
              <q-banner v-if="step === 1" class="bg-primary text-white q-px-lg">
                {{ $t("pct.step.1.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 2"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("pct.step.2.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 3"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("pct.step.3.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 4"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("pct.step.3.description") }}
              </q-banner>
              <q-banner
                v-else-if="step === 5"
                class="bg-primary text-white q-px-lg"
              >
                {{ $t("pct.step.3.description") }}
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
      :PCTEntryNumber="patentNumber"
      :PCTBibliographicdata="this.epoData"
      :PCTpriority="priority"
      :PCTpriorities="priorities"
      :customerName="this.name"
      :customerPhone="this.phone"
      :customerEmail="this.email"
      :companyName="companyName"
      :taxNumber="taxNumber"
      :countryName="countryName"
      :stateName="stateName"
      :cityName="cityName"
      :addressName="addressName"
      :postCode="postCode"
      :chapter="chapter"
      :PCTfees="this.fee"
      :ipservice="title"
      :step="step"
      :OfficialFee="parseFloat(this.fee.total.officialFee).toFixed(2)"
      :TranslationFee="parseFloat(this.fee.total.translateFee).toFixed(2)"
      :ServiceFee="parseFloat(this.fee.total.serviceFee).toFixed(2)"
      :totalPayabale="parseFloat(this.fee.total.total).toFixed(2)"
      @modal="GetValue"
    />
  </div>
  <q-dialog v-model="guide">
    <q-card>
      <q-card-section>
        <div class="text-h6">
          {{ $t("pct.title") }}
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
        <p>{{ $t("pct.guides") }}</p>
      </q-card-section>

      <q-separator />
    </q-card>
  </q-dialog>
</template>

<script>
import { ref, defineAsyncComponent, onMounted } from "vue";
import { api } from "boot/axios";
import { useQuasar } from "quasar";
import { useI18n } from "vue-i18n";
import { useMeta } from "quasar";
import { useRouter } from "vue-router";

const metaData = {
  title: "PCT Entry In Türkiye",
  titleTemplate: (title) => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: "canonical",
      href: "https://patent.istanbul/pct-national-phase-entry-in-turkiye",
    },
  ],
};
export default {
  components: {
    Feedback: defineAsyncComponent(() => import("pages/feedback")),
  },
  data() {
    return {
      message: false,
    };
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
    finishPCTEntry() {
      let orderlink = (Math.random() + 1).toString(36).substring(2);

      let ref = this.reference;
      if (ref === "") {
        ref = "---";
      }
      let late_service_fee = 0;
      let late_official_fee = 0;

      if (this.fee.serviceFee[1]?.["amount"] != undefined) {
        late_service_fee = this.fee.serviceFee[1]["amount"];
      }

      if (this.fee.officialFee[3]?.["amount"] != undefined) {
        late_official_fee = this.fee.officialFee[3]["amount"];
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
        .post("/api/v1/order/pctentry", {
          renewal_fee: this.fee.officialFee[0]["amount"],
          pct_entry_fee: this.fee.officialFee[1]["amount"],
          examination_fee: this.fee.officialFee[2]["amount"],
          late_official_fee: late_official_fee,
          official_fee: this.fee.total.officialFee,
          application_number: this.patentNumber,
          publication_date: this.epoData.response.bibliographicData.publicationDate,
          invention_title: this.epoData.response.bibliographicData.inventionTitle,
          applicant: this.epoData.response.bibliographicData.applicant,
          service_fee: parseFloat(this.fee.total.serviceFee).toFixed(2),
          priority_length: this.fee.priority.count,
          priority_fee: this.fee.total.priorityFee,
          translation_fee: this.fee.total.translateFee,
          translation_quantity: this.fee.translationFee.wordCount,
          late_service_fee: late_service_fee,
          total: this.fee.total.total,
          user_name: this.name,
          user_phone: this.phone,
          user_email: this.email,
          service: "PCT Entry",
          link: orderlink,
          reference: ref,
        })
        .then((res) => {
          api
            .post("/api/v1/send-mail", {
              type: "PCT Entry",
              name: this.name,
              email: this.email,
              patentNumber: this.epoData.number,
              link: orderlink,
              language: currentLanguage,
            })
            .then(() => {
              this.$router.push({ path: `${t('order')}/` + orderlink });
            });
        })
        .catch((err) => {});
    },

    GetValue(value) {
      if (value === false) {
        this.fixed = false;
      }
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
    const { t } = useI18n({ useScope: "global" });
    const companyName = ref("");
    const taxNumber = ref("");
    const countryName = ref("");
    const stateName = ref("");
    const cityName = ref("");
    const addressName = ref("");
    const postCode = ref("");
    const router = useRouter();
    const dollar = ref(0);

    onMounted(async () => {
      api.get("/api/v1/dollar-exchange-rate").then((res) => {
        dollar.value = res.data.USD;
      });
    });

    function patentQuery(patentNumber) {
      api
        .post("/api/v1/query/" + patentNumber.replace("/", "") + "/pct")
        .then((res) => {
          console.log(res)
          if (res.data.status == false) {
            var dialogInfo = {
              title: "",
              message: "",
            };
            if (res.data.code === 404) {
              dialogInfo = {
                title: t("pct.step.1.error.patentNumber.title"),
                message: t("pct.step.1.error.patentNumber.description"),
              };
            } else if (res.data.code === 410) {
              dialogInfo = {
                title: t("pct.step.1.error.serverError.title"),
                message: t("pct.step.1.error.serverError.description"),
              };
            } else if (res.data.code === 411) {
              dialogInfo = {
                title: t("pct.step.1.error.missingFee.title"),
                message: t("pct.step.1.error.missingFee.description"),
              };
            } else {
              dialogInfo = {
                title: t("pct.step.1.error.serverError.title"),
                message: t("pct.step.1.error.serverError.title"),
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
            this.loading = false;
            this.step++;
            if (
              typeof this.epoData.priorities !== "undefined" &&
              this.epoData.priorities.length > 0
            ) {
              this.priority = "yes";
              this.priorities = this.epoData.priorities;
            }
          }
          this.loading = false;
        })
        .catch((err) => {
          $q.dialog({
            title: t("pct.step.1.error.serverError.title"),
            icon: "error",
            type: "negative",
            message: t("pct.step.1.error.serverError.description"),
            color: "negative",
          });
          this.loading = false;
        });
    }

    function stepperNext() {
      this.step++;
      this.virtualStep++;
    }
    function stepperPrevious() {
      this.step--;
    }

    function add(title, date) {
      if (title && date) {
        // Array consists of two keys, number and date
        this.priorities.push({
          documentNumber: title,
          date: date.replaceAll("-", "/"),
        });
        this.title = null;
        this.date = null;
      }
    }
    return {
      stateName,
      cityName,
      addressName,
      postCode,
      countryName,
      taxNumber,
      companyName,
      companyInfoData: ref([]),
      validationMessage: ref(""),
      chapter: ref("1"),
      inception: ref(false),
      chapterRules: [
        (val) => (val && val.length > 0) || t("pct.rules.notNullable"),
      ],
      patentNumber: ref(""),
      patentNumberRules: [
        (val) => (val && val.length > 0) || t("pct.rules.notNullable"),
      ],
      priority: ref("no"),
      priorityTitle: ref(""),
      priorityDate: ref(""),
      priorities: ref([]),
      baseDate: ref(null),
      step: ref(1),
      virtualStep: ref(1),
      epoData: ref(null),
      fee: ref([]),
      eur: ref(0),
      dollar,
      lateofficialfees: ref(null),
      priorityfeestotal: ref(null),
      officialfeestotal: ref(null),
      officialfeesusd: ref(null),
      totaltranslationfees: ref(null),
      totalPayable: ref(null),
      lateservicefees: ref(null),
      totalservicefees: ref(null),
      name: ref(""),
      email: ref(""),
      phone: ref(""),
      patentQuery,
      stepperNext,
      stepperPrevious,
      add,
      loading,
      priorityAmount: ref(0),
      totalFee: ref(0),
      fixed: ref(false),
      guide: ref(false),
      title: ref("PCT ENTRY"),
      reference: ref(""),
      step1Submit() {
        this.loading = true;
        this.patentQuery(this.patentNumber);
      },
      step2Submit() {
        /**
         * api call
         * url : /api/v1/query/{patentNumber}/pct/fees
         * parameters: priorities
         */
        this.loading = true;
        this.fee = [];
        api
          .post(
            "/api/v1/query/" + this.patentNumber.replace("/", "") + "/pct/fees",
            {
              priorities: this.priorities,
            }
          )
          .then((res) => {
            this.fee = res.data;
            if(this.fee.status == 'close-to-expire'){
              this.step = 4;
            }
          });

        this.step++;
      },

      card: ref(false),
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
