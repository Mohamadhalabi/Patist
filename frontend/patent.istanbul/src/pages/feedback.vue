<template>
  <q-dialog v-model="fixed" @hide="this.$emit('modal',fixed)">
    <q-card>
      <q-card-section style="background-color: #3a4f72;padding:20px">
        <div class="text-h6 feedback-title" style="color: white">{{ $t("feedback.form.title") }}
          <q-btn class="float-right close-button" flat v-close-popup round dense icon="close"  />
        </div>
      </q-card-section>
      <q-separator />
      <q-form @submit="onSubmit" @reset="onReset" className="q-gutter-md ">
        <q-card-section class="scroll feedback-form">
          <div>
          <span class="text-subtitle2">
            {{ $t("feeback.email.input") }}
          </span>
            <br><br>
            <q-input
              class="q-mb-md"
              outlined v-model="email"
              label="Email"
              lazy-rules
              :rules="[
              (val) => (val && val.length > 0) || $t('email.error.message'),
              isValidEmail,
              ]"
            />
          </div>
          <q-separator />
          <br>
          <div>
          <span class="text-subtitle2">
            {{ $t("feeback.visit.reason") }}
          </span>
            <br><br>
            <div class="q-gutter-md row">
              <q-select
                class="q-mb-md"
                label="IP Service"
                transition-show="flip-up"
                transition-hide="flip-down"
                v-model="selectedOption"
                :options="[this.ipservice]"
                style="width: 100%"
                outlined
                :rules="[
                (val) => (val && val.length > 0) || $t('subject.error.message'),
                ]"
              />
            </div>
          </div>
          <q-separator />
          <br>
          <div>
          <span class="text-subtitle2">
            {{ $t("feeback.provide") }}
          </span>
            <div class="q-pa-md" style="width: 100%">
              <q-input
                v-model="message"
                outlined
                label="Please Enter Your Message"
                transition-show="flip-up"
                transition-hide="flip-down"
                type="textarea"
                :rules="[
                (val) => (val && val.length > 0) || $t('message.error.message'),
                ]"
              />
            </div>
          </div>
          <div class="text-center">
            <q-btn
              style="background-color: #3a4f72"
              text-color="white"
              label="Submit"
              type="submit"
            />
          </div>
        </q-card-section>
      </q-form>
      <q-separator />
    </q-card>
  </q-dialog>
</template>
<script>
import { useQuasar } from "quasar";
import { ref } from "vue";
import { api } from "boot/axios";
import {useI18n} from "vue-i18n";

export default {
  props: ['ipservice','step','EPpatentNumber','EPBibliographicdata'
    ,'customerName','customerEmail','customerPhone','companyInfoData'
    ,'companyName','taxNumber','countryName','stateName','cityName'
    ,'addressName','postCode','relationship','fees','PCTEntryNumber',
    'PCTBibliographicdata','PCTpriority','PCTpriorities','chapter','PCTfees','euroExchangeRate','totalPayabale',
    'translation_fee',"orderlink",'OfficialFee','ServiceFee','TranslationFee','patentAnnuityNumber','registrationDate',
    "inventionName" ,"applicantName" , "applicantAddress" ,"paymentDates","renewalFee","exchangeRate","reference"
  ],
  created() {
    this.selectedOption = this.ipservice
    this.subject = this.ipservice
    this.currentStep = this.step
    this.patentNumber = this.EPpatentNumber
    this.publicationNumber = this.PCTEntryNumber
    this.eurorate = this.euroExchangeRate
    this.total = this.totalPayabale
    this.orderurl = "[quote#"+this.orderlink+"]"
    this.customermail = this.customerEmail
    this.officialFee = this.OfficialFee
    this.serviceFee = this.ServiceFee
    this.translationfee = this.TranslationFee
    this.patentannuityNo = this.patentAnnuityNumber
    this.InventionName = this.inventionName
    this.ApplicantName = this.applicantName
    this.RegistrationDate = this.registrationDate
    this.ApplicantAddress = this.applicantAddress
    this.PaymentDates = this.paymentDates
    this.RenewalFee = this.renewalFee
    this.ExchangeRate = this.exchangeRate
    this.Reference = this.reference
  },
  data(){
    return {
      selectedOption: '',
    }
  },
  mounted() {
    if(this.selectedOption === "EP VALIDATION") {
      this.epValidationFeeback()
    }
    if(this.selectedOption === "PCT ENTRY") {
      this.pctEntryFeedback()
    }
    if(this.selectedOption === "PATENT ANNUITY") {
      this.PatentAnnuityFeedback()
    }

  },
  methods: {
    isValidEmail(val) {
      const emailPattern =
        /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
      return emailPattern.test(val) || "Invalid email";
    },
  },
  setup() {
    const $q = useQuasar();
    const email = ref(null);
    const message = ref(null);
    const subject = ref(null);
    const currentStep = ref(null);
    const patentNumber = ref("");
    const publicationNumber = ref("");
    const total = ref(null);
    const eurorate = ref(null);
    const invoice = ref([]);
    const priorities = ref([]);
    const translationfee = ref(null);
    const orderurl = ref("");
    const customermail = ref("");
    const officialFee = ref("");
    const serviceFee = ref("");
    const patentannuityNo = ref("");
    const InventionName = ref("");
    const ApplicantName = ref("");
    const RegistrationDate = ref("");
    const ApplicantAddress = ref("");
    const PaymentDates = ref([]);
    const RenewalFee = ref("")
    const ExchangeRate = ref(0)
    const Reference = ref("")
    const { t } = useI18n({useScope: 'global'})

    function showDefault() {
      const dialog = $q.dialog({
        message: "Message sending...",
        progress: true,
        persistent: false,
        ok: false,
      });
    }

    function epValidationFeeback(){
      if(this.currentStep === 1) {
        this.message ='\n---\n{'+t('step')+': '+this.step+', '+t('ep.step.1.title')+': '+this.patentNumber+'}'
      }
      if (this.currentStep === 2) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('ep.step.1.title')+': '+this.patentNumber+'},'+
          '\n\n{'+t('step')+': '+this.step+', '
          +t('ep.step.2.label.filingDate')+': '+this.EPBibliographicdata.response.bibliographicData.publicationDate+', '
          +t('ep.step.2.label.inventionTitle')+': '+this.EPBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('ep.step.2.label.applicant')+': '+this.EPBibliographicdata.response.bibliographicData.applicant
          +'}'
      }
      if (this.currentStep === 3) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('ep.step.1.title')+': '+this.patentNumber+'},'+
          '\n\n{'+t('step')+': '+2+', '
          +t('ep.step.2.label.filingDate')+': '+this.EPBibliographicdata.response.bibliographicData.publicationDate+', '
          +t('ep.step.2.label.inventionTitle')+': '+this.EPBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('ep.step.2.label.applicant')+': '+this.EPBibliographicdata.response.bibliographicData.applicant+
          '}\n\n{'+t('step')+': '+this.step+', '
          +t('name.surname')+': '+this.customerName+', '
          +t('phone.number')+':'+this.customerPhone+', '
          +t('email.address')+':'+this.customerEmail
          +'}'
      }
      if (this.currentStep === 4) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('ep.step.1.title')+': '+this.patentNumber+'},'+
          '\n\n'+t('step')+': '+2+', '
          +t('ep.step.2.label.filingDate')+': '+this.EPBibliographicdata.response.bibliographicData.publicationDate+', '
          +t('ep.step.2.label.inventionTitle')+': '+this.EPBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('ep.step.2.label.applicant')+': '+this.EPBibliographicdata.response.bibliographicData.applicant+
          '}\n\n{'+t('step')+': '+this.step+', '
          +t('name.surname')+': '+this.customerName+', '
          +t('phone.number')+':'+this.customerPhone+', '
          +t('email.address')+':'+this.customerEmail
          +'}'+
          '\n\n{'+t('step')+': '+this.step+', '
          +t('official.fee')+':'+this.officialFee+' € , '
          +t('translation.fee')+': '+this.translationfee+' € , '
          +t('service.fee')+':'+this.serviceFee+'€ , '
          +t('total')+':'+this.total+' €, '
          +'}'
      }
    }
    function pctEntryFeedback(){
      if(this.currentStep === 1) {
        this.message ='\n---\n{'+t('step')+': '+this.step+', '+t('pct.step.1.title')+': '+this.publicationNumber+'}'
      }
      if (this.currentStep === 2) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('pct.step.1.title')+': '+this.publicationNumber+'}'+
          '\n\n{'+t('step')+': '+this.step+', '
          +t('pct.step.1.title')+': '+this.publicationNumber+', '
          +t('pct.step.2.label.filingDate')+': '+this.PCTBibliographicdata.response.bibliographicData.publicationReference.date+', '
          +t('pct.step.2.label.applicationDate')+': '+this.PCTBibliographicdata.response.bibliographicData.applicationDate+', '
          +t('pct.step.2.label.inventionTitle')+': '+this.PCTBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('pct.step.2.label.applicant')+': '+this.PCTBibliographicdata.response.bibliographicData.applicant+', '
          +t('pct.step.2.label.priorities')+': '+this.PCTpriority+', '
          +'}'
      }
      if (this.currentStep == 3) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('pct.step.1.title')+': '+this.publicationNumber+'}'+
          '\n\n{'+t('step')+': '+2+', '
          +t('pct.step.1.title')+': '+this.publicationNumber+', '
          +t('pct.step.2.label.filingDate')+': '+this.PCTBibliographicdata.response.bibliographicData.publicationReference.date+', '
          +t('pct.step.2.label.applicationDate')+': '+this.PCTBibliographicdata.response.bibliographicData.applicationDate+', '
          +t('pct.step.2.label.inventionTitle')+': '+this.PCTBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('pct.step.2.label.applicant')+': '+this.PCTBibliographicdata.response.bibliographicData.applicant+', '
          +t('pct.step.2.label.priorities')+': '+this.PCTpriority+', '
          +'}'
          +'\n\n{'+t('step')+': '+this.step+', '
          +t('pct.close-to-expire');
      }
      if (this.currentStep === 4) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('pct.step.1.title')+': '+this.publicationNumber+'}'+
          '\n\n{'+t('step')+': '+2+', '
          +t('pct.step.1.title')+': '+this.publicationNumber+', '
          +t('pct.step.2.label.filingDate')+': '+this.PCTBibliographicdata.response.bibliographicData.publicationReference.date+', '
          +t('pct.step.2.label.applicationDate')+': '+this.PCTBibliographicdata.response.bibliographicData.applicationDate+', '
          +t('pct.step.2.label.inventionTitle')+': '+this.PCTBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('pct.step.2.label.applicant')+': '+this.PCTBibliographicdata.response.bibliographicData.applicant+', '
          +t('pct.step.2.label.priorities')+': '+this.PCTpriority+', '
          +'}'
          +'\n\n{'+t('step')+': '+3+', '
          +t('pct.close-to-expire')+
          '\n\n{'+t('step')+': '+this.step+', '
          +t('name.surname')+': '+this.customerName+', '
          +t('phone.number')+':'+this.customerPhone+', '
          +t('email.address')+':'+this.customerEmail
          +'}'
      }
      if (this.currentStep === 5){
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('pct.step.1.title')+': '+this.publicationNumber+'}'+
          '\n\n{'+t('step')+': '+2+', '
          +t('pct.step.1.title')+': '+this.publicationNumber+', '
          +t('pct.step.2.label.filingDate')+': '+this.PCTBibliographicdata.response.bibliographicData.publicationReference.date+', '
          +t('pct.step.2.label.applicationDate')+': '+this.PCTBibliographicdata.response.bibliographicData.applicationDate+', '
          +t('pct.step.2.label.inventionTitle')+': '+this.PCTBibliographicdata.response.bibliographicData.inventionTitle+', '
          +t('pct.step.2.label.applicant')+': '+this.PCTBibliographicdata.response.bibliographicData.applicant+', '
          +t('pct.step.2.label.priorities')+': '+this.PCTpriority+', '
          +'}'
          +'\n\n{'+t('step')+': '+3+', '
          +t('pct.close-to-expire')+
          '\n\n{'+t('step')+': '+4+', '
          +t('name.surname')+': '+this.customerName+', '
          +t('phone.number')+':'+this.customerPhone+', '
          +t('email.address')+':'+this.customerEmail
          +'}'+
          '\n\n{'+t('step')+': '+this.step+', '
          +t('official.fee')+':'+this.officialFee+' € , '
          +t('translation.fee')+': '+this.translationfee+' € , '
          +t('service.fee')+':'+this.serviceFee+'€ , '
          +t('total')+':'+this.total+' €, '
          +'}'
      }
    }
    function PatentAnnuityFeedback(){
      if(this.currentStep === 1) {
        this.message ='\n---\n{'+t('step')+': '+this.step+', '+t('ep.step.1.title')+': '+this.patentannuityNo+'}'
      }
      if(this.currentStep === 2) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('annuity.step.1.input.label')+': '+this.patentannuityNo+'}'+
          '\n\n{'+t('step')+': '+this.step+', '
          +t('invention.name')+' '+this.InventionName+', '
          +t('annuity.step.2.label.applicantName')+': '+this.ApplicantName+', '
          +t('registration.date')+': '+this.RegistrationDate+', '
          +t('annuity.step.2.label.applicantAddress')+': '+this.ApplicantAddress+', '
        for (let i = 0; i < this.PaymentDates.length; i++) {
          const obj = this.PaymentDates[i];
          this.message += `, Year: ${obj.paymentDate}, Fee: ${obj.paymentAmount}`;
          if (i < this.PaymentDates.length - 1) {
            this.message += ', ';
          }
        }
        this.message +="}";
      }

      if(this.currentStep === 3) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('annuity.step.1.input.label')+': '+this.patentannuityNo+'}'+
          '\n\n{'+t('step')+': '+2+', '
          +t('invention.name')+' '+this.InventionName+', '
          +t('annuity.step.2.label.applicantName')+': '+this.ApplicantName+', '
          +t('registration.date')+': '+this.RegistrationDate+', '
          +t('annuity.step.2.label.applicantAddress')+': '+this.ApplicantAddress+', '
        for (let i = 0; i < this.PaymentDates.length; i++) {
          const obj = this.PaymentDates[i];
          this.message += `, Year: ${obj.paymentDate}, Fee: ${obj.paymentAmount}`;
          if (i < this.PaymentDates.length - 1) {
            this.message += ', ';
          }
        }
        this.message +="}"
          +'\n\n{'+t('step')+': '+this.step+', '
          +t('renewal.fee')+': '+this.RenewalFee+'TL, '
          +t('service.fee')+':'+this.ServiceFee+'€, '
          +t('eur')+':'+this.ExchangeRate+','
          +t('total')+':'+parseFloat(this.total).toFixed(2)+'€'
        +'}'
      }

      if(this.currentStep === 4) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('annuity.step.1.input.label')+': '+this.patentannuityNo+'}'+
          '\n\n{'+t('step')+': '+2+', '
          +t('invention.name')+' '+this.InventionName+', '
          +t('annuity.step.2.label.applicantName')+': '+this.ApplicantName+', '
          +t('registration.date')+': '+this.RegistrationDate+', '
          +t('annuity.step.2.label.applicantAddress')+': '+this.ApplicantAddress+', '
        for (let i = 0; i < this.PaymentDates.length; i++) {
          const obj = this.PaymentDates[i];
          this.message += `, Year: ${obj.paymentDate}, Fee: ${obj.paymentAmount}`;
          if (i < this.PaymentDates.length - 1) {
            this.message += ', ';
          }
        }
        this.message +="}"
          +'\n\n{'+t('step')+': '+3+', '
          +t('renewal.fee')+': '+this.RenewalFee+'TL, '
          +t('service.fee')+':'+this.ServiceFee+'€, '
          +t('eur')+':'+this.ExchangeRate+','
          +t('total')+':'+parseFloat(this.total).toFixed(2)+'€'
          +'}'

          +'\n\n{'+t('step')+': '+this.currentStep+', '
          +t('name.surname')+': '+this.customerName+', '
          +t('email.address')+':'+this.customerEmail+', '
          +t('phone.number')+':'+this.customerPhone+','
          +'}'
      }

      if(this.currentStep === 5) {
        this.message =
          '\n---\n{'+t('step')+': '+1+', '+t('annuity.step.1.input.label')+': '+this.patentannuityNo+'}'+
          '\n\n{'+t('step')+': '+2+', '
          +t('invention.name')+' '+this.InventionName+', '
          +t('annuity.step.2.label.applicantName')+': '+this.ApplicantName+', '
          +t('registration.date')+': '+this.RegistrationDate+', '
          +t('annuity.step.2.label.applicantAddress')+': '+this.ApplicantAddress+', '
        for (let i = 0; i < this.PaymentDates.length; i++) {
          const obj = this.PaymentDates[i];
          this.message += `, Year: ${obj.paymentDate}, Fee: ${obj.paymentAmount}`;
          if (i < this.PaymentDates.length - 1) {
            this.message += ', ';
          }
        }
        this.message +="}"
          +'\n\n{'+t('step')+': '+3+', '
          +t('renewal.fee')+': '+this.RenewalFee+'TL, '
          +t('service.fee')+':'+this.ServiceFee+'€, '
          +t('eur')+':'+this.ExchangeRate+','
          +t('total')+':'+parseFloat(this.total).toFixed(2)+'€'
          +'}'
          +'\n\n{'+t('step')+': '+4+', '
          +t('name.surname')+': '+this.customerName+', '
          +t('email.address')+':'+this.customerEmail+', '
          +t('phone.number')+':'+this.customerPhone+','
          +'}'
          +'\n\n{'+t('step')+': '+this.currentStep+', '
          +t('reference')+': '+this.Reference+', '
          +t('Official.Fees')+':'+parseFloat(this.officialFee).toFixed(2)+'€, '
          +t('Service.Fees')+':'+parseFloat(this.serviceFee).toFixed(2)+'€,'
          +t('total')+':'+parseFloat(this.total).toFixed(2)
          +'€ }'
      }

    }

    return {
      Reference,
      ExchangeRate,
      RenewalFee,
      PaymentDates,
      ApplicantAddress,
      RegistrationDate,
      ApplicantName,
      InventionName,
      patentannuityNo,
      serviceFee,
      officialFee,
      customermail,
      orderurl,
      email,
      message,
      subject,
      currentStep,
      eurorate,
      total,
      showDefault,
      patentNumber,
      invoice,
      publicationNumber,
      epValidationFeeback,
      pctEntryFeedback,
      PatentAnnuityFeedback,
      priorities,
      translationfee,
      show_label: ref(true),
      options: ['EP VALIDATION','PCT ENTRY','PATENT ANNUITY'],
      feedback: ref('yes'),
      fixed: ref(true),
      text:ref(""),
      onSubmit() {
        const dialog = $q.dialog({
          message: "Feedback sending...",
          progress: true,
          persistent: false,
          ok: false,
        });
        api.post("/api/v1/feedback", {
          email: email.value,
          message: message.value,
          subject: subject.value,
        })
          .then((res) => {
            if (res.data.success) {
              dialog.update({
                progress: false,
                message:
                  "<div class='text-center'><i class='q-icon notranslate material-icons' style='font-size: 62px;color:green'>done</i><br><br></div>",
                html: true,
              });
            }
          })
          .catch((err) => {
          });
      },
    };
  },
};
</script>
