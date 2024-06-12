<template>
  <div ref="document">
    <table class="demo" style="max-width: 80%">
    <tbody>
    <tr>
      <td style="text-align:center">
        <p class="table-header">
          {{ $t("case.info") }}
        </p>
      </td>
      <td style="width: 80%">
        <p class="table-items" style="margin: 0 0 12px">{{service}}</p>
        <p class="summary-text" style="margin: 0 0 12px" v-if="service =='EP Validation'">{{ $t("ep.order-table.quote.number") }}: <span class="summary-span">{{application_number}}</span></p>
        <p class="summary-text" style="margin: 0 0 12px" v-else>{{ $t("app.number") }}: <span class="summary-span">{{application_number}}</span></p>
        <p class="summary-text" style="margin:0 0 12px">{{ $t("Title") }}: <span class="summary-span">{{title}}</span></p>
        <p class="summary-text" style="margin: 0 0 12px" v-if="service =='EP Validation'">{{ $t("ep.order-table.quote.publication-date") }}: <span class="summary-span">{{publication_date}}</span></p>
        <p class="summary-text" style="margin: 0 0 12px" v-else>{{ $t("publication.date") }}: <span class="summary-span">{{publication_date}}</span></p>
        <p class="summary-text" style="margin: 0 0 12px">{{ $t("your.ref") }}: <span class="summary-span">{{reference}}</span></p>
      </td>
    </tr>
    <tr style="background-color:#fafafa">
      <td style="text-align:center" class="table-items">
        <p class="table-header">{{ $t("quote") }}</p></td>
      <td>
        <p class="summary-text"> {{ $t("Official.Fees") }}:
          <span style="float: right">{{parseFloat(official_fee).toFixed(2)}} {{currencySymbol}}</span>
        </p>
        <ul style="list-style: none" v-if="service =='Patent Annuity'">
          <li>
            <p class="summary-sub-item">
              {{ $t("Patent.Annuity.official.fees") }}:
              <span class="summary-span"> {{patent_annuity_official_fee}} ₺</span>
              <span style="float: right;font-size: 14px" v-if="late_official_fee == 0" >(EUR/TRY={{exchange_rate}})</span>
            </p>
          </li>
          <li v-if="late_official_fee > 0">
            <p class="summary-sub-item">
              {{ $t("Official.Late.Fee") }}:
              <span class="summary-span"> {{late_official_fee}} ₺</span>
              <span style="float: right;font-size: 14px">(EUR/TRY={{exchange_rate}})</span>
            </p>
          </li>
        </ul>
        <ul style="list-style: none" v-if="service =='EP Validation'">
          <li>
            <p class="summary-sub-item">
              {{ $t("ep.val.official.fee") }}:
              <span class="summary-span"> {{ep_validation_official_fee}} ₺</span>
              <span style="float: right;font-size: 14px" v-if="official_fee_extension == null" >(EUR/TRY={{exchange_rate}})</span>
            </p>
            <p class="summary-sub-item" v-if="official_fee_extension > 0">
              {{ $t("extension.of.three.months") }}:
              <span class="summary-span"> {{official_fee_extension}} ₺</span>
              <span style="float: right;font-size: 14px">(EUR/TRY={{exchange_rate}})</span>
            </p>
          </li>
        </ul>
        <ul style="list-style: none" v-if="service =='PCT Entry'">
          <li>
            <p class="summary-sub-item">
              {{ $t("Renewal.Fee") }}:
              <span class="summary-span"> {{renewal_fee}} ₺</span>
            </p>
            <p class="summary-sub-item">
              {{ $t("PCT.Entry.Fee") }}:
              <span class="summary-span"> {{pct_entry_fee}} ₺</span>
            </p>
            <p class="summary-sub-item">
              {{ $t("Examination.Fee") }}:
              <span class="summary-span"> {{examination_fee}} ₺</span>
              <span style="float: right;font-size: 14px" v-if="late_official_fee == 0 && priority_length == 0">(USD/TRY={{exchange_rate}})</span>
            </p>
            <p class="summary-sub-item" v-if="priority_length > 0">
              {{ $t("Priority.Fee") }} ({{priority_length}} X {{priority_fee / priority_length }} ₺) :
              <span class="summary-span"> {{priority_fee}} ₺</span>
              <span style="float: right;font-size: 14px" v-if="late_official_fee == 0">(USD/TRY={{exchange_rate}})</span>
            </p>
            <p class="summary-sub-item" v-if="late_official_fee >0">
              {{ $t("Late.official.fees") }}:
              <span class="summary-span"> {{late_official_fee}} ₺</span>
              <span style="float: right;font-size: 14px">(USD/TRY={{exchange_rate}})</span>
            </p>
          </li>
        </ul>
        <hr style="border: 0;height: 1px;background:#888888	;">
        <p class="summary-text"> {{ $t("Service.Fees") }}:
          <span style="float: right">{{service_fee}} {{currencySymbol}}</span>
        </p>
        <ul style="list-style: none" v-if="service =='EP Validation'">
          <li>
            <p class="summary-sub-item">{{ $t("EP.Validation.service.fees") }}:
              <span class="summary-span">{{service_fee - late_service_fee}} {{currencySymbol}}</span>
            </p>
            <p class="summary-sub-item" v-if="late_service_fee > 0 && service =='PCT Entry'">{{ $t("PCT.Entry.Additional.Time.Fee") }}:
              <span class="summary-span">{{late_service_fee}} {{currencySymbol}}</span>
            </p>
            <p class="summary-sub-item" v-if="late_service_fee !=null && service =='EP Validation'">{{ $t("EP.Validation.Additional.Time.Fee") }}:
              <span class="summary-span">{{late_service_fee}} {{currencySymbol}}</span>
            </p>
          </li>
        </ul>
        <ul style="list-style: none" v-if="service =='PCT Entry'">
          <li>
            <p class="summary-sub-item">{{ $t("PCT.Entry.service.fees") }}:
              <span class="summary-span">{{service_fee - late_service_fee}} {{currencySymbol}}</span>
            </p>
            <p class="summary-sub-item" v-if="late_service_fee > 0 ">{{ $t("PCT.Entry.Additional.Time.Fee") }}:
              <span class="summary-span">{{late_service_fee}} {{currencySymbol}}</span>
            </p>
          </li>
        </ul>
        <ul style="list-style: none" v-if="service =='Patent Annuity'">
          <li>
            <p class="summary-sub-item">{{ $t("Patent.Annuity.service.fees") }}:
              <span class="summary-span"> {{service_fee}} {{currencySymbol}}</span>
            </p>
          </li>
        </ul>
        <hr style="border: 0;height: 1px;background:#888888	;" v-if="service =='PCT Entry' || service =='EP Validation'">
        <p class="summary-text" v-if="service =='PCT Entry' || service =='EP Validation'"> {{ $t("Translation.Fees") }}:
          <span style="float: right">{{translation_fee}} {{currencySymbol}}</span>
        </p>
        <ul style="list-style: none" v-if="service =='PCT Entry' || service =='EP Validation'">
          <li>
            <p class="summary-sub-item">{{ $t("Estimated.words.in.claims.description.and.figures") }}:
              <span>{{translation_quantity}}</span>
            </p>
          </li>
          <li>
            <p class="summary-sub-item">{{ $t("Trans.fees") }}:
              <span>{{translation_fee}}({{ translation_fee / translation_quantity}} {{currencySymbol}}/word)</span>
            </p>
          </li>
        </ul>
        <hr style="border: 0;height: 1px;background:#888888	;">
        <p class="summary-text"> {{ $t("total.payable") }}:
          <span style="float: right">{{ parseFloat(totalPayable).toFixed(2)}} {{currencySymbol}}</span>
        </p>
      </td>
    </tr>
    <tr>
      <td style="text-align:center" class="table-items">
        <p class="table-header">{{ $t("contact.info") }}:</p>
      </td>
      <td>
        <p class="summary-text" style="margin: 0 0 12px"> {{ $t("user.name") }}:
          <span>{{user_name}}</span>
        </p>
        <p class="summary-text" style="margin: 0 0 12px"> {{ $t("user.email") }}:
          <span>{{user_email}}</span>
        </p>
        <p class="summary-text" style="margin: 0 0 12px"> {{ $t("user.phone") }}:
          <span>{{user_phone}}</span>
        </p>
      </td>
    </tr>
    </tbody>
  </table>
  </div>
</template>

<script>
import {ref} from "vue";
import {useRouter} from "vue-router";
import {api} from "boot/axios";

export default {
  name: "Order-table",
  setup(){
    const orderData = ref([]);
    const router = useRouter()
    const currencySymbol = ref("");
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
    const renewal_fee = ref("");
    const pct_entry_fee = ref("");
    const examination_fee = ref("");
    const late_official_fee = ref("");
    const priority_length = ref("");
    const priority_fee = ref("");
    const ep_validation_official_fee = ref("");
    const patent_annuity_official_fee = ref("");
    const getOrderData = async () => {

      const { data } = await api.post('/api/v1/order-details', {
        'link': router.currentRoute.value.params.slug,
      })

      orderData.value = JSON.parse(data[0]['details']);

      application_number.value = orderData.value['Application Number'];
      title.value = orderData.value['Title']
      service.value =  orderData.value['service']
      reference.value = orderData.value['Your Reference']
      publication_date.value = orderData.value['Publication Date']
      official_fee.value = orderData.value['Official fees']
      ep_validation_official_fee.value = orderData.value['EP validation official fee'];
      official_fee_extension.value = orderData.value['Extension of 3 months official fees']
      service_fee.value = orderData.value['Service Fees']
      translation_fee.value = orderData.value['Translation Fees']
      late_service_fee.value = orderData.value['late_service_fee']
      translation_quantity.value = orderData.value['Estimated words in claims, description, and figures']
      totalPayable.value = orderData.value['Total Payable']
      user_name.value = orderData.value['Name']
      user_phone.value = orderData.value['Phone']
      user_email.value = orderData.value['Email']
      renewal_fee.value = orderData.value['renewal_fee']
      pct_entry_fee.value = orderData.value['pct_entry_fee'];
      examination_fee.value = orderData.value['examination_fee'];
      late_official_fee.value = orderData.value['late_official_fee'];
      priority_length.value = orderData.value['priority_length'];
      priority_fee.value = orderData.value['priority_fee'];
      patent_annuity_official_fee.value = orderData.value['Patent annuity official fee']


      if(service.value =='EP Validation') {
        api.post("/api/v1/query/" + application_number.value)
          .then((res) => {
            exchange_rate.value = res.data.fee.currency.eur
          })
      }
      else if (service.value =="PCT Entry"){
        api.get("/api/v1/dollar-exchange-rate").then((res) => {
          exchange_rate.value = res.data.USD;
        });
      }
      else if(service.value =="Patent Annuity"){
         let patentNumber = application_number.value.replace("/", "-");

        api.post("/api/v1/query/" + patentNumber + "/tr")
          .then((res)=>{
            exchange_rate.value = res.data.fee.currency.eur
          })
      }
      if(service.value === 'PCT Entry'){
        currencySymbol.value = "$"
      }
      else{
        currencySymbol.value = "€"
      }
    };
    getOrderData()
    return{
      patent_annuity_official_fee,
      ep_validation_official_fee,
      priority_fee,
      priority_length,
      late_official_fee,
      examination_fee,
      pct_entry_fee,
      renewal_fee,
      user_name,
      user_phone,
      user_email,
      totalPayable,
      translation_quantity,
      translation_fee,
      late_service_fee,
      service_fee,
      exchange_rate,
      official_fee_extension,
      reference,
      publication_date,
      service,
      title,
      application_number,
      orderData,
      currencySymbol,
      official_fee

    }
  }


}


</script>


<style scoped>

</style>
