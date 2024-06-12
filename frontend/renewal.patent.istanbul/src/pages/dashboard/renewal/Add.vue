<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="q-pa-md row items-start q-mt-xl">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Add New Application Number
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                <div class="row">
                  <div class="col-md-6 col-sm-12 w-100">
                    <div class="q-mr-md q-mb-md">
                      <q-input
                      outlined
                      bottom-slots
                      v-model="patentNumber"
                      label="Example: 2020/00001"
                      mask="####/########"
                      name="patentNumber"
                      maxlength="12"
                    >
                      <template v-slot:hint>
                        Enter application number
                      </template>
                    </q-input>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12 w-100">
                    <div>
                      <q-btn
                      class="font-size-14"
                        size="lg"
                        type="submit"
                        color="primary"
                        icon="search"
                        label="Inquire Application Number"
                        @click="submitSearch"
                      />
                    </div>
                  </div>
                  <p class="text-body2 q-mt-md">
                    As a result of the query, you will see whether the
                    application number you are querying is valid or not, and if
                    so, the renewal status. If you get a non-renewable warning,
                    you'll see why.
                  </p>
                </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-ma-md text-center">
          <p class="text-body1">
            Need something different? You can
            <a :href="`/dashboard/contact`">contact</a> us here.
          </p>
        </div>
        <div class="q-pa-md row items-start q-mt-sm" v-if="requestStatus">
          <div class="doc-note doc-note--danger" v-if="requestCode == 404">
            <p>
              {{ message }}
            </p>
          </div>
          <div class="doc-note doc-note--danger" v-else-if="requestCode == 400">
            <p>daha önce eklendi</p>
          </div>
          <div class="doc-note doc-note--danger" v-else-if="requestCode == 201">
            <p>
              {{ message }}
            </p>
          </div>
          <div style="width: 100%" v-else>
            <div class="doc-note doc-note--tip">
              <p>
                Are you sure you want to create the renewal request for this
                number?
              </p>
              <q-btn
                class="q-my-md"
                color="primary"
                icon="add"
                label="Add"
                @click="saveNewRenewal"
              />
            </div>
            <div class="q-ma-md">
              <h4 class="text-h4 text-center">Application Details</h4>
              <h5 class="text-h5 q-mb-sm">Invention</h5>
              <p>{{ this.response.bulus.bulusAdi }}</p>
              <h5 class="text-h5 q-mb-sm">Applican</h5>
              <p>{{ this.response.applicant.name }}</p>
              <h5 class="text-h5 text-center q-mb-sm">Payment Dates</h5>
              <table>
                <tr>
                  <th style="width: 33.3%; font-weight: bold">Order</th>
                  <th style="width: 33.3%; font-weight: bold">Year</th>
                  <th style="width: 33.3%; font-weight: bold">Amount</th>
                </tr>
                <tr v-for="(payment, index) in this.response.odemeTarihleri">
                  <th style="width: 33.3%">{{ payment.paymentNumber }}</th>
                  <th style="width: 33.3%">{{ payment.paymentDate }}</th>
                  <th style="width: 33.3%">{{ payment.paymentAmount }} TRY</th>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div v-if="isFailed">
          <div class="q-ma-md">
            <div class="doc-note doc-note--fail">
              <p>
                We are unable to continue your transaction due to the provider-related access problem.
              </p>
              <p>
                We have recorded the patent number you inquired and your contact
                information. We will get back to you as soon as possible.
              </p>
            </div>
          </div>
          <div class="q-ma-md">
            <div class="doc-note doc-note--fail">
              <div class="row">
                <div class="col-auto self-center" v-if="!this.isSaved">
                  <div>
                    Would you like to manually save your renewal request
                    <strong>{{ this.patentNumber }}</strong> ?
                  </div>
                </div>
                <div class="col" v-if="!this.isSaved">
                  <q-btn
                    class="q-mx-md"
                    color="primary"
                    icon="add"
                    label="Add Manually"
                    @click="saveFailedRequestNumber"
                  />
                </div>
                <div class="col self-center" v-else>
                  Was recorded. We will get back to you as soon as possible.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>
<script>
import { ref } from "vue";
import { Loading } from "quasar";
import { useI18n } from "vue-i18n";
import { searchApplicationNo, saveNewRenewalRequest } from "src/api/fetch";
import { saveFailedRequest } from "src/api/failed-request";

export default {
  name: "AppNumberMonitoring",
  methods: {
    submitSearch() {
      Loading.show();
      searchApplicationNo(this.patentNumber)
        .then((res) => {
          if (res.status == false) {
            this.requestStatus = true;
            this.requestCode = 404;
            this.message = res.message;
          } else {
            this.requestStatus = true;
            this.message = res.message;
            this.requestCode = res.code;
            this.response = res.data;
          }
          Loading.hide();
        })
        .catch((err) => {
          Loading.hide();
          this.isFailed = true;
        });
    },
    saveFailedRequestNumber() {
      saveFailedRequest({ number: this.patentNumber })
        .then((res) => {
          this.$q.notify({
            message: res.message,
            color: "primary",
            icon: "check",
          });
        })
        .catch((err) => {
          this.$q.notify({
            message: err.message,
            color: "negative",
            icon: "check",
          });
        });
      this.isFailed = true;
      this.isSaved = true;
    },
    saveNewRenewal() {
      Loading.show();
      saveNewRenewalRequest(this.patentNumber)
        .then((res) => {
          // notify
          if (res.status == true) {
            this.$q.notify({
              message: res.message,
              color: "primary",
              icon: "check",
            });
          } else {
            this.$q.notify({
              message: res.message,
              color: "negative",
              icon: "check",
            });
          }
          Loading.hide();
        })
        .catch((err) => {
          Loading.hide();
          this.isFailed = true;
        });
    },
  },
  setup() {
    const { t } = useI18n({ useScope: "global" });
    const form = ref({
      reference_no: "",
      cc: "",
      region: "",
      annuity_status: "",
      filing_date: "",
      renewal_date: "",
      official_fee_eur: "",
      official_fee_original: "",
      official_fee_currency: "",
      agent_fee_eur: "",
      agent_fee_original: "",
      agent_fee_currency: "",
      service_fee_eur: "",
      service_fee_exchange_rate: "",
      agent_fee_exhange_rate: "",
    });

    return {
      isSaved: ref(false),
      isFailed: ref(false),
      t,
      form,
      patentNumber: ref(""),
      saveButton: ref(false),
      requestStatus: ref(false),
      requestCode: ref(""),
      message: ref(""),
      response: ref({}),
    };
  },
};
</script>

<style scoped>
.container {
  padding: 20px;
}

.add-monitoring-input {
  width: 100%;
  font-size: 16px;
}

.button {
  padding: 10px;
  margin-left: 10px;
  background-color: #3a4f72;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.table {
  margin-top: 10px;
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd; /* Add border */
}

.table th,
.table td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.table th {
  background-color: #3a4f72;
  color: #fff;
}
.button-div {
  font-size: 14px;
  margin-top: auto;
  margin-bottom: auto;
}
@media screen and (min-width: 1440px) {
  .watch-by-id-second-column {
    margin-left: 80px;
  }
}
@media screen and (max-width: 1440px) {
  .watch-by-id-second-column {
    margin-top: 30px;
  }
}

.scroll {
  max-height: 50vh;
  min-width: 50vh;
  overflow-y: auto;
}

.row {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}
.label {
  color: #75808a;
  margin-bottom: 0.25rem;
}

.emails,
.application-numbers {
  margin-bottom: 1rem;
}

.model,
.reference {
  margin-bottom: 1rem;
}

.payment-status {
  margin-top: 1rem;
  margin-bottom: 1rem;
}

.bank-information {
  border-radius: 10px;
  background-color: #f2f2f2;
  padding: 1rem;
  margin-top: 1rem;
  margin-bottom: 1rem;
}
.q-page-container {
  padding-top: 0px !important;
}
.doc-note--tip {
  background-color: #daf8e1;
  border-color: #21ba45;
}
.doc-note--tip .doc-note__title,
.doc-note--tip .doc-link,
.doc-note--tip .doc-token {
  color: #147029;
}
.doc-note__title {
  font-weight: 700;
  letter-spacing: 0.7px;
  padding-bottom: 8px;
}
.doc-note > p,
.doc-note > ul {
  margin-bottom: 0;
}
.doc-note > p,
.doc-note > ul {
  margin-bottom: 0;
}
.doc-note {
  font-size: 16px;
  border-radius: 4px;
  margin: 16px 0;
  padding: 16px;
  border-width: 1px;
  border-style: solid;
  width: 100%;
}
.doc-note--danger {
  background-color: #ffdfe3;
  border-color: #c10015;
}
.doc-note--fail {
  background-color: #dbdee8;
  border-color: #6b7184;
}
.doc-note--danger .doc-note__title,
.doc-note--danger .doc-link,
.doc-note--danger .doc-token {
  color: #c10015;
}

table {
  border-collapse: collapse;
  width: 100%;
}
tr {
  border: 1px solid black;
}
td,
th {
  padding: 8px;
  font-weight: normal;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
@media screen and (max-width: 600px) {
  .w-100{
    width: 100% !important;
  }
  .font-size-14{
    font-size: 14px !important;
  }
}
</style>
