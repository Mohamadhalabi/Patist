<template>
  <q-form @submit="companyInfo()">
    <div class="row q-pa-md">
      <div class="col-md-6 col-sm-12 col-xs-12 q-pa-md">
        <q-input
          filled
          v-model="company"
          label="Company"
          hint="Company name"
          @update:model-value="$emit('companyName',company)"
          lazy-rules
          :rules="[
            (val) => (val && val.length > 0) || $t('company.name.message'),
          ]"
        />
      </div>
      <div class="col-md-6 col-sm-12 col-xs-12 q-pa-md">
        <q-input
          filled v-model="taxNumber"
          label="Tax"
          hint="Tax number"
          @update:model-value="$emit('taxNumber',taxNumber)"
        />
      </div>
    </div>
    <div class="col-md-12 q-pa-md">
      <div class="row q-mt-md q-mb-md">
        <div class="col-md-6 col-sm-12 col-xs-12 q-pa-md">
          <q-select
            filled
            :options="countryOptions"
            :option-value="'id'"
            :option-label="'name'"
            v-model="country"
            label="Country"
            hint="Select country"
            @update:model-value="showStates(country), $emit('countryName',country)"
            :rules="[myRule]"
          />
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 q-pa-md">
          <q-select
            filled
            :options="stateOptions"
            :option-value="'id'"
            :option-label="'name'"
            v-model="state"
            label="State"
            hint="Select state"
            @update:model-value="showCities(state), $emit('stateName',state)"
          />
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 q-pa-md">
          <q-select
            filled
            label="City"
            hint="Select city"
            v-model="city"
            :options="cityOptions"
            :option-value="'id'"
            :option-label="'name'"
            @update:model-value="$emit('cityName',city)"

          />
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 q-pa-md">
          <q-input
            filled
            v-model="address"
            label="Address"
            hint="Address"
            @update:model-value="$emit('addressName',address)"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || $t('address.message')]"
          />
        </div>
      </div>
      <div>
        <q-input
          filled
          type="number"
          v-model="postCode"
          label="Post Code"
          @update:model-value="$emit('postCode',postCode)"
          hint="Post Code"
          class="q-pa-md"
        />
      </div>
    </div>
    <q-stepper-navigation>
      <q-btn color="primary" label="Continue" type="submit" @click="CompanyInfoSubmit()" />
    </q-stepper-navigation>
  </q-form>
</template>

<script>
import { ref } from "vue";
import { api } from "boot/axios";
export default {
  name: "CompanyInfo",
  props: ["step","companyInfoData"],

  // companyInfo Emit
  methods: {
    CompanyInfoSubmit(){
      if(this.company !=='' && this.address !=='' && this.country !=='') {
        this.$emit("submit", "submit");
      }
    },
    myRule (val) {
      if (val === "") {
        return 'Please select a country'
      }
    },
    companyInfo() {
      this.$emit("companyInfo", {
        postCode: this.postCode,
        address: this.address,
        country: this.country,
        state: this.state,
        city: this.city,
        company: this.company,
        taxNumber: this.taxNumber,
      });
    },
    backStep() {
      this.$emit("backStep", true);
    },
  },
  setup() {
    const country = ref("");
    const state = ref("");
    const city = ref("");
    const postCode = ref("");
    const address = ref("");
    const countryOptions = ref([]);
    const stateOptions = ref([]);
    const cityOptions = ref([]);
    const company = ref("");
    const taxNumber = ref("");
    const companyInfo = ref([]);

    // Get Countries
    const getCountries = async () => {
      const { data } = await api.get("/api/v1/countries");
      countryOptions.value = data;
    };

    // Get States
    const showStates = async (country) => {
      const { data } = await api.get(`/api/v1/states/${country.id}`);
      stateOptions.value = data;
    };

    // Get Cities
    const showCities = async (state) => {
      const { data } = await api.get(`/api/v1/cities/${state.id}`);
      cityOptions.value = data;
    };

    // Load Countries
    getCountries();

    //get order data
    function order() {

      var parts = window.location.href.split('/');
      var lastSegment = parts.pop() || parts.pop();  // handle potential trailing slash


      api.post('/api/v1/order-details', {
        'link': lastSegment,
      })
        .then((res) => {
          this.orderData = res.data[0];
          if(this.orderData.company !==null){
            this.company = this.orderData.company
          }
          if(this.orderData.tax_number !== null){
            this.taxNumber = this.orderData.tax_number
          }
          if(this.orderData.country != null){
            this.country = this.orderData.country
          }
          if(this.orderData.state != null){
            this.state = this.orderData.state
          }
          if(this.orderData.city != null){
            this.city = this.orderData.city
          }
          if(this.orderData.address != null){
            this.address = this.orderData.address
          }
          if(this.orderData.post_code != null){
            this.postCode = this.orderData.post_code
          }
        });
    }

    return {
      order,
      country,
      state,
      city,
      postCode,
      address,
      countryOptions,
      stateOptions,
      cityOptions,
      showStates,
      showCities,
      company,
      taxNumber,
    };
  },
};
</script>
