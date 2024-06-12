<template>
  <q-page class="main-container">
    <div class="container">
      <div class="row">
        <div class="col-md-10 q-mx-auto col-xs-12">
          <h1 class="text-h2 text-center text-primary page-header">
            Instruction (#{{ link }})
          </h1>
          <button
            class="float-right feedback-btn feedback-txt"
            @click="fixed = true"
          >
            {{ $t("feedback") }}
          </button>
          <q-stepper v-model="step" ref="stepper" color="primary" animated>
            <q-step
              :name="1"
              :title="[$t('Accept.the.quote')]"
              icon="settings"
              :done="done1"
            >
              <div class="row quote-page">
                <div class="col-lg-9 col-md-9">
                  <div
                    class="col-md-12 result justify-center"
                    style="text-align: -webkit-center"
                  >
                    <order-table />
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 quote-buttons">
                  <q-btn
                    class="generate-pdf-button"
                    @click="
                      () => {
                        generatePDF();
                      }
                    "
                    icon="picture_as_pdf"
                    :label="[$t('generate.pdf')]"
                  />
                  <q-btn
                    class="accept-quote-button"
                    @click="
                      () => {
                        done1 = true;
                        aceeptQuote();
                        step = 2;
                      }
                    "
                    icon="task_alt"
                    color="primary"
                    :label="[$t('Accept.the.quote')]"
                  />
                </div>
                <div class="q-ml-lg" v-if="OrderStatus == 'close-to-expire'">
                  <p
                    class="q-ml-xl"
                    style="font-size: 18px; color: #823723"
                    v-if="checkAdditionalFeeDate === true"
                  >
                    <span style="font-size: 25px">*</span> After
                    {{ additionalFeeDate }} the application could require
                    additional time fees. Please proceed with your instruction
                    until {{ additionalFeeDate }} to avoid additional time fees.
                  </p>
                  <p class="q-ml-xl" style="font-size: 18px; color: #823723">
                    <span style="font-size: 25px">*</span> The quote is valid
                    until {{ expirationDate }}. After this time the application
                    term will be expired and we will not be able to procced with
                    the instruction. Please procced with your instruction as
                    soon as possible to avoid service distruption.
                  </p>
                </div>
              </div>
            </q-step>
            <q-step
              :name="2"
              title="Additional info"
              icon="create_new_folder"
              :done="done2"
            >
              <div class="col-md-12 q-pa-md" v-if="service == 'EP Validation'">
                <p>{{ $t("ep.step.5.description") }}</p>
                <q-list>
                  <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                      <q-radio
                        v-model="relationship"
                        val="Service Relationship"
                      />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>{{
                          $t("service.relationship")
                        }}</q-item-label>
                    </q-item-section>
                  </q-item>

                  <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                      <q-radio v-model="relationship" val="Handover" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>{{ $t("handover") }}</q-item-label>
                    </q-item-section>
                  </q-item>

                  <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                      <q-radio
                        v-model="relationship"
                        val="According to the contract"
                      />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>{{
                          $t("according.to.the.contract")
                        }}</q-item-label>
                    </q-item-section>
                  </q-item>

                  <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                      <q-radio v-model="relationship" val="Applicant" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>{{ $t("applicant") }}</q-item-label>
                    </q-item-section>
                  </q-item>

                  <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                      <q-radio v-model="relationship" val="Company owner" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>{{ $t("company.owner") }}</q-item-label>
                    </q-item-section>
                  </q-item>

                  <q-item tag="label" v-ripple>
                    <q-item-section avatar>
                      <q-radio v-model="relationship" val="Not Selected" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>Not Selected</q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </div>

              <div
                class="col-md-12 q-pa-md"
                v-if="orderData.service == 'PCT Entry'"
              >
                <p>{{ $t("pct.step.6.label.chapter") }}</p>
                <div class="q-gutter-sm">
                  <q-radio
                    v-model="chapter"
                    val="1"
                    label="Chapter 1"
                    selected
                  />
                  <q-radio v-model="chapter" val="2" label="Chapter 2" />
                </div>
              </div>

              <br />
              <p class="q-pa-md">{{ $t("contact.info") }}</p>
              <CompanyInfo
                @companyName="getcompanyName"
                @taxNumber="getTaxNumber"
                @countryName="getCountry"
                @stateName="getState"
                @cityName="getCity"
                @addressName="getAddress"
                @postCode="getPostCode"
                @stepback="getstepback"
                @submit="getsubmit"
              ></CompanyInfo>
            </q-step>
            <q-step :name="3" title="Checkout" icon="payment" :done="done3">
              <div>
                <div class="row">
                  <div class="col-lg-6 col-md-5 col-sm-10 col-xs-10 result">
                    <h6 class="text-left text-primary order-titles">
                      {{ $t("summary") }}:
                    </h6>

                    <table class="demo">
                      <tbody>
                      <tr>
                        <td style="text-align: center">
                          <p class="table-header">{{ $t("case.info") }}</p>
                        </td>
                        <td style="line-height: 10px; width: 75%">
                          <p class="table-items" style="margin: 0 0 12px">
                            {{ service }}
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            <strong v-if="service == 'EP Validation'"
                            >{{ $t("ep.order-table.quote.number") }}:
                            </strong>
                            <strong v-else>{{ $t("app.number") }}</strong>
                            <span class="summary-span">{{
                                application_number
                              }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("Title") }}:
                            <span class="summary-span">{{ title }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            <strong v-if="service == 'EP Validation'"
                            >{{
                                $t("ep.order-table.quote.publication-date")
                              }}:
                            </strong>
                            <strong v-else
                            >{{ $t("publication.date") }}:
                            </strong>

                            <span class="summary-span">{{
                                publication_date
                              }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("your.ref") }}:
                            <span class="summary-span">{{ reference }}</span>
                          </p>
                        </td>
                      </tr>
                      <tr style="background-color: #fafafa">
                        <td style="text-align: center" class="table-items">
                          <p class="table-header">{{ $t("quote") }}</p>
                        </td>
                        <td>
                          <p class="summary-text">
                            {{ $t("Official.Fees") }}:
                            <span style="float: right"
                            >{{ parseFloat(official_fee).toFixed(2) }}
                                {{ currencySymbol }}</span
                            >
                          </p>
                          <ul
                            style="list-style: none"
                            v-if="service == 'Patent Annuity'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{ $t("Patent.Annuity.official.fees") }}:
                                <span class="summary-span">
                                    {{ patent_annuity_official_fee }}
                                    ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      late_official_fee == 0 &&
                                      exchange_rate_from_db == null
                                    "
                                >(EUR/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      late_official_fee == 0 &&
                                      exchange_rate_from_db != null
                                    "
                                >(EUR/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="late_official_fee > 0"
                              >
                                {{ $t("Patent.annuity.late.official.fee") }}:
                                <span class="summary-span">
                                    {{ late_official_fee }}
                                    ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="exchange_rate_from_db == null"
                                >(EUR/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="exchange_rate_from_db != null"
                                >(EUR/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                            </li>
                          </ul>
                          <ul
                            style="list-style: none"
                            v-if="service == 'EP Validation'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{ $t("ep.val.official.fee") }}:
                                <span class="summary-span">
                                    {{ ep_validation_official_fee }}
                                    ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      official_fee_extension == null &&
                                      exchange_rate_from_db == null
                                    "
                                >(EUR/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      official_fee_extension == null &&
                                      exchange_rate_from_db != null
                                    "
                                >(EUR/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="official_fee_extension > 0"
                              >
                                {{ $t("extension.of.three.months") }}:
                                <span class="summary-span">
                                    {{ official_fee_extension }}
                                    ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="exchange_rate_from_db == null"
                                >(EUR/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-else
                                >(EUR/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                            </li>
                          </ul>
                          <ul
                            style="list-style: none"
                            v-if="service == 'PCT Entry'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{ $t("Renewal.Fee") }}:
                                <span class="summary-span">
                                    {{ orderData.renewal_fee }} ₺</span
                                >
                              </p>
                              <p class="summary-sub-item">
                                {{ $t("PCT.Entry.Fee") }}:
                                <span class="summary-span">
                                    {{ orderData.pct_entry_fee }} ₺</span
                                >
                              </p>
                              <p class="summary-sub-item">
                                {{ $t("Examination.Fee") }}:
                                <span class="summary-span">
                                    {{ orderData.examination_fee }} ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      orderData.late_official_fee == 0 &&
                                      orderData.priority_length == 0 &&
                                      exchange_rate_from_db == null
                                    "
                                >(USD/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      orderData.late_official_fee == 0 &&
                                      orderData.priority_length == 0 &&
                                      exchange_rate_from_db != null
                                    "
                                >(USD/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="orderData.priority_length > 0"
                              >
                                {{ $t("Priority.Fee") }} ({{
                                  orderData.priority_length
                                }}
                                X
                                {{
                                  orderData.priority_fee /
                                  orderData.priority_length
                                }}
                                ₺) :
                                <span class="summary-span">
                                    {{ orderData.priority_fee }} ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="
                                      orderData.late_official_fee == 0 &&
                                      exchange_rate_from_db == null
                                    "
                                >(USD/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-else
                                >(USD/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="orderData.late_official_fee > 0"
                              >
                                {{ $t("Late.official.fees") }}:
                                <span class="summary-span">
                                    {{ orderData.late_official_fee }} ₺</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-if="exchange_rate_from_db == null"
                                >(USD/TRY={{ exchange_rate }})</span
                                >
                                <span
                                  style="float: right; font-size: 14px"
                                  v-else
                                >(USD/TRY={{ exchange_rate_from_db }})</span
                                >
                              </p>
                            </li>
                          </ul>
                          <hr
                            style="
                                border: 0;
                                height: 1px;
                                background: #888888;
                              "
                          />
                          <p class="summary-text">
                            Service Fees:
                            <span style="float: right"
                            >{{ service_fee }} {{ currencySymbol }}</span
                            >
                          </p>
                          <ul
                            style="list-style: none"
                            v-if="service == 'EP Validation'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{ $t("EP.Validation.service.fees") }}:
                                <span class="summary-span"
                                >{{ service_fee - late_service_fee }}
                                    {{ currencySymbol }}</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="
                                    late_service_fee != null &&
                                    orderData.service == 'EP Validation'
                                  "
                              >
                                {{ $t("EP.Validation.Additional.Time.Fee") }}:
                                <span class="summary-span"
                                >{{ late_service_fee }}
                                    {{ currencySymbol }}</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="
                                    orderData.late_service_fee > 0 &&
                                    orderData.service == 'PCT Entry'
                                  "
                              >
                                {{ $t("PCT.Entry.Additional.Time.Fee") }}:
                                <span class="summary-span"
                                >{{ orderData.late_service_fee }}
                                    {{ currencySymbol }}</span
                                >
                              </p>
                            </li>
                          </ul>
                          <ul
                            style="list-style: none"
                            v-if="orderData.service == 'PCT Entry'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{ $t("PCT.Entry.service.fees") }}:
                                <span class="summary-span"
                                >{{ service_fee - late_service_fee }}
                                    {{ currencySymbol }}</span
                                >
                              </p>
                              <p
                                class="summary-sub-item"
                                v-if="orderData.late_service_fee > 0"
                              >
                                {{ $t("PCT.Entry.Additional.Time.Fee") }}:
                                <span class="summary-span"
                                >{{ orderData.late_service_fee }}
                                    {{ currencySymbol }}</span
                                >
                              </p>
                            </li>
                          </ul>
                          <ul
                            style="list-style: none"
                            v-if="orderData.service == 'Patent Annuity'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{ $t("Patent.Annuity.service.fees") }}:
                                <span class="summary-span"
                                >{{ service_fee }}
                                    {{ currencySymbol }}</span
                                >
                              </p>
                            </li>
                          </ul>
                          <hr
                            v-if="service != 'Patent Annuity'"
                            style="
                                border: 0;
                                height: 1px;
                                background: #888888;
                              "
                          />
                          <p
                            class="summary-text"
                            v-if="service != 'Patent Annuity'"
                          >
                            Translation Fees:
                            <span style="float: right"
                            >{{ translation_fee }}
                                {{ currencySymbol }}</span
                            >
                          </p>
                          <ul
                            style="list-style: none"
                            v-if="service != 'Patent Annuity'"
                          >
                            <li>
                              <p class="summary-sub-item">
                                {{
                                  $t(
                                    "Estimated.words.in.claims.description.and.figures"
                                  )
                                }}:
                                <span>{{ translation_quantity }}</span>
                              </p>
                            </li>
                            <li v-if="service != 'Patent Annuity'">
                              <p class="summary-sub-item">
                                {{ $t("Trans.fees") }}:
                                <span
                                >{{ translation_fee }}({{
                                    translation_fee / translation_quantity
                                  }}
                                    {{ currencySymbol }}/{{ $t("word") }})
                                  </span>
                              </p>
                            </li>
                          </ul>
                          <hr
                            style="
                                border: 0;
                                height: 1px;
                                background: #888888;
                              "
                          />
                          <p class="summary-text">
                            {{ $t("total.payable") }}:
                            <span style="float: right"
                            >{{ parseFloat(totalPayable).toFixed(2) }}
                                {{ currencySymbol }}</span
                            >
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center" class="table-items">
                          <p class="table-header">{{ $t("contact.info") }}</p>
                        </td>
                        <td>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("user.name") }}:
                            <span>{{ user_name }} </span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("user.email") }}:
                            <span>{{ user_email }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("user.phone") }}:
                            <span>{{ user_phone }}</span>
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center" class="table-items">
                          <p class="table-header">{{ $t("company.info") }}</p>
                        </td>
                        <td>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("company.input.name.label") }}:
                            <span>{{ company }} {{ this.companyName }}</span>
                          </p>
                          <p
                            class="summary-text"
                            style="margin: 0 0 12px"
                            v-if="service == 'EP Validation'"
                          >
                            {{ $t("relationship") }}:
                            <span v-if="relationship_from_db == null">{{
                                this.relationship
                              }}</span>
                            <span v-else>{{ relationship_from_db }}</span>
                          </p>
                          <p
                            class="summary-text"
                            style="margin: 0 0 12px"
                            v-if="service == 'PCT Entry'"
                          >
                            {{ $t("chapter") }}:
                            <span v-if="chapter_from_db == null">{{
                                this.chapter
                              }}</span>
                            <span v-else>{{ chapter_from_db }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("tax.number") }}:
                            <span>{{ taxnumber }}{{ TaxNumber }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("post.code") }}:
                            <span>{{ postCode }}{{ Postcode }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("country") }}:
                            <span>{{ country["name"] }}{{ Country }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("state") }}:
                            <span>{{ State }}{{ state["name"] }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("city") }}:
                            <span>{{ City }}{{ city["name"] }}</span>
                          </p>
                          <p class="summary-text" style="margin: 0 0 12px">
                            {{ $t("address") }}:
                            <span>{{ Address }}{{ this.address }}</span>
                          </p>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                  <div
                    class="col-lg-5 col-md-5 col-sm-10 col-xs-10 q-pl-xl payment-option"
                  >
                    <br /><br />
                    <p
                      class="text-left text-primary"
                      style="
                        text-decoration: underline;
                        text-underline-offset: 8px;
                        font-size: 22px;
                      "
                    >
                      {{ $t("status") }}:
                    </p>
                    <q-banner
                      dense
                      inline-actions
                      class="text-white bg-green q-my-md"
                    >
                      <p style="font-size: 16px; margin: 0">
                        Thank you. Your instruction has been received. Our team
                        will contact you after a review of the instruction.
                      </p>
                    </q-banner>
                    <p style="font-size: 16px; font-weight: bold">
                      {{ $t("instruction.status") }}:
                      <span
                        style="
                          font-size: 16px;
                          font-weight: normal;
                          color: darkgreen;
                        "
                      >{{ $t("instruction.received") }}</span
                      >
                    </p>
                    <p style="font-size: 16px; font-weight: bold">
                      {{ $t("payment.status") }}:
                      <span
                        style="
                          font-size: 16px;
                          font-weight: normal;
                          color: darkgreen;
                        "
                        v-if="orderData.order_status == 'success'"
                      >Payment received</span
                      >
                      <span
                        style="
                          font-size: 16px;
                          font-weight: normal;
                          color: #ba4f33;
                        "
                        v-else
                      >
                        {{ $t("Payment.not.received") }}
                      </span>
                    </p>
                    <q-btn
                      class="button-font q-mb-xl"
                      color="primary"
                      label="Contact"
                      @click="inception = true"
                    />
                    <p
                      class="text-left text-primary"
                      style="
                        text-decoration: underline;
                        text-underline-offset: 8px;
                        font-size: 22px;
                      "
                    >
                      {{ $t("Payment.options") }}:
                    </p>
                    <q-tabs
                      v-model="method"
                      dense
                      class="text-grey"
                      active-color="primary"
                      active-bg-color="ehehe"
                      indicator-color="primary"
                      align="justify"
                      narrow-indicator
                    >
                      <q-tab name="BankTransfer" label="Bank Transfer" />
                      <q-tab name="CreditCard" label="Credit Card" />
                    </q-tabs>

                    <div v-if="orderData.order_status == 'success'">
                      <iframe
                        :src="`https://api.patent.istanbul/payment/${link}`"
                        width="700"
                        style="background-color: white !important"
                        height="660"
                        frameBorder="0"
                      ></iframe>
                    </div>
                    <div v-else>
                      <div v-if="this.method === 'BankTransfer'">
                        <div class="col-sm-12">
                          <table
                            border="0"
                            cellpadding="0"
                            cellspacing="0"
                            style="
                              border-collapse: collapse;
                              width: 95%;
                              margin: 1.5em;
                              font-size: 16px;
                            "
                          >
                            <tbody>
                            <tr
                              style="
                                  border-bottom: 1px solid #ccc;
                                  line-height: 3em;
                                  margin-bottom: 10px;
                                "
                            >
                              <td style="font-weight: bold">
                                BANK ACCOUNT NAME:
                                <span
                                  style="
                                      float: right;
                                      font-weight: normal !important;
                                    "
                                >Proust Patent Danışmanlık Ltd. Şti.</span
                                >
                              </td>
                            </tr>
                            <tr
                              style="
                                  border-bottom: 1px solid #ccc;
                                  line-height: 3em;
                                  margin-bottom: 10px;
                                "
                              v-if="
                                  service === 'EP Validation' ||
                                  service === 'Patent Annuity'
                                "
                            >
                              <td style="font-weight: bold">
                                EUR IBAN:
                                <span
                                  style="
                                      float: right;
                                      font-weight: normal !important;
                                    "
                                >TR200003200000000095762617</span
                                >
                              </td>
                            </tr>
                            <tr
                              style="
                                  border-bottom: 1px solid #ccc;
                                  line-height: 3em;
                                  margin-bottom: 10px;
                                "
                              v-else
                            >
                              <td style="font-weight: bold">
                                USD IBAN:
                                <span
                                  style="
                                      float: right;
                                      font-weight: normal !important;
                                    "
                                >TR070003200000000093673471</span
                                >
                              </td>
                            </tr>
                            <tr
                              style="
                                  border-bottom: 1px solid #ccc;
                                  line-height: 3em;
                                  margin-bottom: 10px;
                                "
                            >
                              <td style="font-weight: bold">
                                SWIFT CODE:
                                <span
                                  style="
                                      float: right;
                                      font-weight: normal !important;
                                    "
                                >TEBUTRISXXX</span
                                >
                              </td>
                            </tr>
                            <tr
                              style="
                                  border-bottom: 1px solid #ccc;
                                  line-height: 3em;
                                  margin-bottom: 10px;
                                "
                            >
                              <td style="font-weight: bold">
                                BANK:
                                <span
                                  style="
                                      float: right;
                                      font-weight: normal !important;
                                      max-width: 40%;
                                      text-align: end;
                                      line-height: 25px;
                                    "
                                >Türk Ekonomi Bankası A.Ş. TEB Kampüs C ve D
                                    Blok Saray Mah.Sokullu Cd.No:7A-7B Ümraniye
                                    34768/İSTANBUL
                                  </span>
                              </td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div v-if="this.method === 'CreditCard'">
                      <iframe
                        :src="`https://api.patent.istanbul/payment/${link}`"
                        class="iyzico-form"
                        style="background-color: white !important"
                        height="660"
                        frameBorder="0"
                      ></iframe>
                    </div>
                  </div>
                  <q-dialog v-model="inception">
                    <q-card class="contact-card-index">
                      <ContactPage
                        :msg="message"
                        :userName="user_name"
                        :userEmail="user_email"
                        :userPhone="user_phone"
                        :orderNumber="link"
                        style="padding-top: 50px"
                      >
                      </ContactPage>
                    </q-card>
                  </q-dialog>
                </div>
              </div>
            </q-step>
          </q-stepper>
        </div>
      </div>
    </div>
  </q-page>

  <div v-if="fixed === true">
    <feedback
      :customerEmail="user_email"
      :ipservice="link"
      :orderlink="link"
      @modal="GetValue"
    />
  </div>
</template>

<script>
import { useRouter } from "vue-router";
import { api } from "boot/axios";
import { defineAsyncComponent, ref } from "vue";
import ContactPage from "pages/ContactPage";
import OrderTable from "pages/Order-table";

export default {
  name: "Orders",
  components: {
    OrderTable,
    ContactPage,
    Feedback: defineAsyncComponent(() => import("pages/feedback")),
    CompanyInfo: defineAsyncComponent(() =>
      import("pages/services/components/CompanyInfo.vue")
    ),
  },
  methods: {
    generatePDF() {
      var currentLanguage = "";
// Array of languages to compare
      const languages = ['de', 'es', 'fr', 'it', 'ja', 'ko', 'tr'];

// Get the current URL
      const currentURL = window.location.href;

// Find the matching language
      const foundLanguage = languages.find(language => currentURL.includes(language));

// Set the current language if found, otherwise default to "en"
      currentLanguage = foundLanguage ? foundLanguage : "en";

      api.get("/api/v1/generate-pdf/" + this.link, { params: { currentLanguage } })
        .then((res) => {
          const pdfData = res.data.pdf;
          const pdfBlob = base64toBlob(pdfData, 'application/pdf');
          const pdfUrl = window.URL.createObjectURL(pdfBlob);
          window.open(pdfUrl, '_blank');
        });

      function base64toBlob(base64Data, contentType) {
        const sliceSize = 1024;
        const byteCharacters = atob(base64Data);
        const byteArrays = [];
        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
          const slice = byteCharacters.slice(offset, offset + sliceSize);
          const byteNumbers = new Array(slice.length);
          for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
          }
          const byteArray = new Uint8Array(byteNumbers);
          byteArrays.push(byteArray);
        }
        const blob = new Blob(byteArrays, { type: contentType });
        return blob;
      }
    },
    GetValue(value) {
      if (value === false) {
        this.fixed = false;
      }
    },
    getcompanyName(value) {
      this.companyName = value;
    },

    getTaxNumber(value) {
      this.taxnumber = value;
    },
    getCountry(value) {
      this.country = value;
    },
    getState(value) {
      this.state = value;
    },
    getCity(value) {
      this.city = value;
    },
    getAddress(value) {
      this.address = value;
    },
    getPostCode(value) {
      this.postCode = value;
    },
    getstepback(value) {
      this.stepback = value;
      if (this.stepback === "stepback") {
        this.step = 1;
      }
    },
  },

  mounted() {
    api.get("/api/v1/dollar-exchange-rate").then((res) => {
      this.dollar = res.data;
    });

    api.get("/api/v1/euro-exchange-rate").then((res) => {
      this.eur = res.data;
    });
  },
  setup() {
    const router = useRouter();
    const orderData = ref([]);
    const orderStatus = ref([]);
    const src = ref("");
    const step = ref(1);
    const done1 = ref(false);
    const done2 = ref(false);
    const done3 = ref(false);
    const companyName = ref("");
    const country = ref("");
    const state = ref("");
    const taxnumber = ref("");
    const city = ref("");
    const address = ref("");
    const postCode = ref("");
    const stepback = ref("");
    const dollar = ref(0);
    const eur = ref(0);
    const relationship = ref("Service Relationship");
    const currencySymbol = ref("");
    const message = ref(false);
    const chapter = ref("1");
    const application_number = ref("");
    const title = ref("");
    const service = ref("");
    const publication_date = ref("");
    const reference = ref("");
    const official_fee = ref("");
    const official_fee_extension = ref("");
    const exchange_rate = ref(0);
    const service_fee = ref("");
    const late_service_fee = ref("");
    const translation_fee = ref("");
    const translation_quantity = ref();
    const totalPayable = ref();
    const user_name = ref("");
    const user_phone = ref("");
    const user_email = ref("");
    const link = ref("");
    const accpted_quote = ref(0);
    const Country = ref("");
    const TaxNumber = ref("");
    const State = ref("");
    const City = ref("");
    const Postcode = ref("");
    const Address = ref("");
    const renewal_fee = ref("");
    const pct_entry_fee = ref("");
    const examination_fee = ref("");
    const priority_length = ref("");
    const priority_fee = ref("");
    const late_official_fee = ref("");
    const ep_validation_official_fee = ref("");
    const exchange_rate_from_db = ref(0);
    const relationship_from_db = ref("");
    const chapter_from_db = ref("");
    const patent_annuity_official_fee = ref("");
    const warning = ref("");
    const expirationDate = ref("");
    const formattedToday = ref("");
    const OrderStatus = ref("");
    const additionalFeeDate = ref("");
    const checkAdditionalFeeDate = ref(false);

    const getData = async () => {
      const { data } = await api.post("/api/v1/order-details", {
        link: router.currentRoute.value.params.slug,
      });
      orderData.value = JSON.parse(data[0]["details"]);

      application_number.value = orderData.value["Application Number"];
      title.value = orderData.value["Title"];
      service.value = orderData.value["service"];
      reference.value = orderData.value["Your Reference"];
      publication_date.value = orderData.value["Publication Date"];
      official_fee.value = orderData.value["Official fees"];
      ep_validation_official_fee.value =
        orderData.value["EP validation official fee"];
      official_fee_extension.value =
        orderData.value["Extension of 3 months official fees"];
      service_fee.value = orderData.value["Service Fees"];
      translation_fee.value = orderData.value["Translation Fees"];
      late_service_fee.value = orderData.value["late_service_fee"];
      translation_quantity.value =
        orderData.value["Estimated words in claims, description, and figures"];
      totalPayable.value = orderData.value["Total Payable"];
      user_name.value = orderData.value["Name"];
      user_phone.value = orderData.value["Phone"];
      user_email.value = orderData.value["Email"];
      accpted_quote.value = orderData.value["accept_quote"];
      companyName.value = orderData.value["company"];
      Country.value = orderData.value["country"];
      TaxNumber.value = orderData.value["tax_number"];
      State.value = orderData.value["state"];
      City.value = orderData.value["city"];
      Postcode.value = orderData.value["post_code"];
      Address.value = orderData.value["address"];
      link.value = router.currentRoute.value.params.slug;
      renewal_fee.value = orderData.value["renewal_fee"];
      pct_entry_fee.value = orderData.value["pct_entry_fee"];
      examination_fee.value = orderData.value["examination_fee"];
      late_official_fee.value = orderData.value["late_official_fee"];
      priority_length.value = orderData.value["priority_length"];
      priority_fee.value = orderData.value["priority_fee"];
      exchange_rate_from_db.value = orderData.value["exchange_rate"];
      relationship_from_db.value = orderData.value["relationship"];
      chapter_from_db.value = orderData.value["chapter"];
      patent_annuity_official_fee.value =
        orderData.value["Patent annuity official fee"];

      if (service.value == "EP Validation") {
        api.post("/api/v1/query/" + application_number.value).then((res) => {
          if (res.data.response.warning != undefined) {
            additionalFeeDate.value =
              res.data.response.warning.quoteChange.quoteChangeDate;

            const today = new Date();
            const yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // Months start at 0!
            let dd = today.getDate();
            if (dd < 10) dd = "0" + dd;
            if (mm < 10) mm = "0" + mm;
            const formattedToday = dd + "-" + mm + "-" + yyyy;
            const additionalFee = new Date(additionalFeeDate.value);
            const currentDate = new Date(formattedToday);
            checkAdditionalFeeDate.value = additionalFee >= currentDate;

            OrderStatus.value = res.data.response.bibliographicData.status;
            warning.value = res.data.response.warning;
            expirationDate.value =
              res.data.response.warning.expiration.expirationDate;
          }
          exchange_rate.value = res.data.fee.currency.eur;
        });
      } else if (service.value == "PCT Entry") {
        api.get("/api/v1/dollar-exchange-rate").then((res) => {
          exchange_rate.value = res.data.USD;
        });
        api
          .post(
            "/api/v1/query/" +
            application_number.value.replace("/", "") +
            "/pct"
          )
          .then((res) => {
            if (res.data.response.warning != undefined) {
              additionalFeeDate.value =
                res.data.response.warning.quoteChange.quoteChangeDate;

              const today = new Date();
              const yyyy = today.getFullYear();
              let mm = today.getMonth() + 1; // Months start at 0!
              let dd = today.getDate();
              if (dd < 10) dd = "0" + dd;
              if (mm < 10) mm = "0" + mm;
              const formattedToday = dd + "-" + mm + "-" + yyyy;
              const additionalFee = new Date(additionalFeeDate.value);
              const currentDate = new Date(formattedToday);
              checkAdditionalFeeDate.value = additionalFee >= currentDate;

              OrderStatus.value = res.data.response.bibliographicData.status;
              warning.value = res.data.response.warning;
              expirationDate.value = res.data.response.expirationDate;
            }
          });
      } else if (service.value == "Patent Annuity") {
        let patentNumber = application_number.value.replace("/", "-");

        api.post("/api/v1/query/" + patentNumber + "/tr").then((res) => {
          exchange_rate.value = res.data.fee.currency.eur;
        });
      }

      if (accpted_quote.value === "1") {
        step.value = 2;
      }

      if (companyName.value != undefined) {
        step.value = 3;
      }

      if (service.value === "PCT Entry") {
        currencySymbol.value = "$";
      } else {
        currencySymbol.value = "€";
      }
    };

    getData();

    function aceeptQuote() {
      api.post("/api/v1/accept-quote", {
        link: router.currentRoute.value.params.slug,
        accept_quote: "1",
        exchange_rate: exchange_rate.value,
      });
    }

    function getDate() {
      const today = new Date();
      const yyyy = today.getFullYear();
      let mm = today.getMonth() + 1; // Months start at 0!
      let dd = today.getDate();

      if (dd < 10) dd = "0" + dd;
      if (mm < 10) mm = "0" + mm;

      formattedToday.value = dd + "-" + mm + "-" + yyyy;
    }
    getDate();

    function submitadditionalinfo() {
      var selectedState = "";
      var selectedCity = "";

      if (state.value.name === undefined) {
        selectedState = "";
      } else {
        selectedState = state.value.name;
      }

      if (city.value.name == undefined) {
        selectedCity = "";
      } else {
        selectedCity = city.value.name;
      }

      if (service.value == "PCT Entry") {
        relationship.value = "";
      }
      if (service.value == "EP Validation") {
        chapter.value = "";
      } else {
        relationship.value = "";
        chapter.value = "";
      }
      api
        .post("/api/v1/additional-info", {
          link: router.currentRoute.value.params.slug,
          relationship: relationship.value,
          company: companyName.value,
          taxnumber: taxnumber.value,
          country: country.value.name,
          state: selectedState,
          city: selectedCity,
          address: address.value,
          postcode: postCode.value,
          chapter: chapter.value,
          service: service.value,
        })
        .then((res) => {
          window.scrollTo(0, 0);
          step.value = 3;
        });
    }

    function getsubmit(value) {

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
      if (value === "submit") {
        api
          .post("/api/v1/send-instruction", {
            type: service.value,
            name: user_name.value,
            email: user_email.value,
            link: link.value,
            language: currentLanguage,
          })
          .then(() => {
            submitadditionalinfo();
          });
      }
    }

    return {
      checkAdditionalFeeDate,
      additionalFeeDate,
      OrderStatus,
      formattedToday,
      warning,
      expirationDate,
      patent_annuity_official_fee,
      chapter_from_db,
      relationship_from_db,
      exchange_rate_from_db,
      ep_validation_official_fee,
      late_official_fee,
      priority_fee,
      priority_length,
      examination_fee,
      pct_entry_fee,
      renewal_fee,
      Address,
      Postcode,
      State,
      City,
      TaxNumber,
      Country,
      accpted_quote,
      link,
      user_email,
      user_phone,
      user_name,
      totalPayable,
      translation_quantity,
      late_service_fee,
      translation_fee,
      service_fee,
      exchange_rate,
      official_fee_extension,
      official_fee,
      publication_date,
      reference,
      title,
      service,
      message,
      relationship: ref("Not Selected"),
      getsubmit,
      aceeptQuote,
      stepback,
      orderData,
      src,
      orderStatus,
      method: ref("BankTransfer"),
      step,
      done1,
      done2,
      done3,
      companyName,
      taxnumber,
      city,
      address,
      postCode,
      country,
      state,
      dollar,
      eur,
      fixed: ref(false),
      inception: ref(false),
      chapter,
      currencySymbol,
      usd: ref("Usd"),
      euro: ref("Euro"),
      application_number,
    };
  },
};
</script>

<style>
* {
  font-family: "EB Garamond", serif;
}
.payment-details-content {
  font-size: 16px;
  margin-bottom: 10px;
}
.payment-option {
  margin-right: 100px;
}
.iyzico-form {
  width: 500px;
}
@media screen and (max-width: 1330px) {
  .iyzico-form {
    width: 380px;
    height: 700px;
    overflow: visible;
  }
}

@media screen and (max-width: 500px) {
  .iyzico-form {
    width: 350px;
    height: 900px;
  }
}
@media screen and (max-width: 380px) {
  .iyzico-form {
    width: 280px;
    height: 900px;
  }
}
.payment-details {
  max-width: 70%;
  margin-left: 130px;
}

@media screen and (max-width: 1440px) {
  .payment-details,
  .order-titles {
    margin-left: 0 !important;
    max-width: 100%;
  }
}
@media screen and (max-width: 1024px) {
  .payment-option {
    margin-left: 50px;
  }
}
@media screen and (max-width: 600px) {
  .payment-option {
    margin-left: 0px;
  }
}
.order-titles {
  text-decoration: underline;
  text-underline-offset: 8px;
  max-width: 80%;
  margin-left: 1%;
}

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

.generate-pdf-button {
  margin-bottom: 10px;
  background: #ba4f33;
  color: white;
}
.generate-pdf-button:hover {
  color: white;
}

.generate-pdf-button,
.accept-quote-button {
  min-width: 220px;
  width: auto;
  margin-left: 40px;
}
.quote-buttons {
  margin-top: 250px;
}

@media screen and (max-width: 1200px) {
  .generate-pdf-button,
  .accept-quote-button {
    margin-left: 3px;
  }
}
@media screen and (max-width: 1023px) {
  .quote-page {
    flex-wrap: wrap-reverse;
  }
  .quote-buttons {
    margin-top: 50px;
  }
  .generate-pdf-button {
    margin-top: 10px;
  }
}
@media screen and (max-width: 458px) {
  .generate-pdf-button,
  .accept-quote-button {
    float: left;
    margin-left: 0;
    margin-bottom: 10px;
  }
  .generate-pdf-button {
    margin-left: 0;
  }
}
</style>
