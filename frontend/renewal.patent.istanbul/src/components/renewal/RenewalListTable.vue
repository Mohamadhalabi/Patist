<template>
  <q-table
    flat
    bordered
    title="Monitor List"
    :rows="this.data"
    :columns="columns"
    row-key="id"
  >
    <template v-slot:loading>
      <q-inner-loading showing color="primary" />
    </template>

    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.label }}
        </q-th>
      </q-tr>
    </template>

    <template v-slot:body="props">
      <q-tr :props="props" v-if="!props.row.reference_no.includes('TST')">
        <q-td auto-width>
          {{ props.row.cc }}
        </q-td>
        <q-td auto-width>
          {{ props.row.reference_no }}
        </q-td>
        <q-td auto-width>
          {{ props.row.region }}
        </q-td>
        <q-td auto-width>
          {{ formatDate(props.row.filing_date) }}
        </q-td>
        <q-td auto-width>
          {{ props.row.renewal_date }}
        </q-td>
        <q-td auto-width>
          <q-badge
            rounded
            color="red"
            label="Rejected"
            v-if="props.row.status == 'rejected'"
          />
          <q-badge
            rounded
            color="green"
            label="Payment Received"
            v-if="props.row.status == 'approved'"
          />
          <q-badge
            rounded
            color="yellow"
            text-color="black"
            label="Payment Pending"
            v-if="props.row.status == 'payment-pending'"
          />
          <q-badge
            rounded
            color="blue"
            label="Pending"
            v-if="props.row.status == 'pending'"
          />
        </q-td>
        <q-td auto-width> 2023-06-13 </q-td>
        <q-td auto-width>
          <a v-if="props.row.status == 'approved'" :href="`/dashboard/renewal/${props.row.id}`">Show Details</a>
          <span v-else-if="props.row.status == 'payment-pending'"><q-btn color="teal" size="md" @click="paymentSingle(props.row.id)">
      <q-icon name="shopping_cart" />
      <div>Pay {{ props.row.total_amount_eur.toFixed(2) }} €</div>
    </q-btn></span>
          <span v-else>Not available</span>
        </q-td>
      </q-tr>
      <q-tr v-show="props.expand" :props="props">
        <q-td colspan="100%">
          <div class="text-left">
            <p class="text-body1 q-ma-xs">
              {{ $t("dashboard.application-Numbers") }} :
              <span></span>
            </p>
          </div>
        </q-td>
      </q-tr>
    </template>
  </q-table>
  <q-dialog v-model="iyzicoDialog" @hide="refreshPage()">
    <q-card>
      <q-card-section>
        <div class="text-h6">Payment Form</div>
      </q-card-section>
      <q-separator />
      <q-card-section style="min-height: 720px">
        <iframe
          scrolling="no"
          frameBorder="0"
          style="height: 720px; width: 500px"
          :src="paymentPageUrl + '&iframe=true'"
        ></iframe>
      </q-card-section>
      <q-separator />
    </q-card>
  </q-dialog>
</template>

<script>
const columns = [
  {
    name: "cc",
    required: true,
    label: "CC",
    align: "left",
    field: (row) => row.type,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "reference",
    required: true,
    label: "Application No",
    align: "left",
    field: "reference",
    sortable: true,
  },
  {
    name: "region",
    required: true,
    label: "Region",
    align: "left",
    field: "region",
    sortable: true,
  },
  {
    name: "filing_date",
    required: true,
    label: "Filing Date",
    align: "left",
    field: "filing_date",
    sortable: true,
  },
  {
    name: "renewal_date",
    required: true,
    label: "Renewal Date",
    align: "left",
    field: "Renewal Date",
    sortable: true,
  },
  {
    name: "status",
    required: true,
    label: "Status",
    align: "left",
    field: "Status",
    sortable: true,
  },
  {
    name: "created_at",
    required: true,
    label: "Created At",
    align: "left",
    field: "created_at",
    sortable: true,
  },
  {
    name: "details",
    required: true,
    label: "Details",
    align: "left",
    field: "Details",
    sortable: true,
  },
];
import { ref } from "vue";
import { paymentSingle } from "src/api/payment";
export default {
  name: "MonitorListTable",
  props: ["data", "details"],
  methods: {
    processNumber(number) {
      return number.replace(/\//g, "_");
    },
    refreshPage() {
      this.$router.go();
    },
    paymentSingle(id) {
      paymentSingle(id)
        .then((res) => {
          console.log(res);
          this.paymentPageUrl = res.paymentPageUrl;
          this.iyzicoDialog = true;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },

  setup() {

    function formatDate(dateString) {
  var parts = dateString.split("-"); // Tarih parçalarına ayır
  var formattedDate = parts[2] + "-" + parts[1] + "-" + parts[0]; // "DD-MM-YYYY" formatından "YYYY-MM-DD" formatına dönüştür

  var date = new Date(formattedDate);
  var year = date.getFullYear();
  var month = ("0" + (date.getMonth() + 1)).slice(-2);
  var day = ("0" + date.getDate()).slice(-2);

  return year + "-" + month + "-" + day;
}

    return {
      columns,
      loading: ref(true),
      iyzicoDialog: ref(false),
      paymentPageUrl: ref(""),
      formatDate
    };
  },
};
</script>
