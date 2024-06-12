<template>
  <q-table
    flat
    bordered
    title="Renewal List"
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
        <!-- <q-td auto-width>
          {{ props.row.reminder }}
        </q-td> -->
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
          {{ new Date(props.row.filing_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
        </q-td>
        <q-td auto-width>
          {{ new Date(props.row.renewal_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
        </q-td>
        <q-td auto-width>
          <q-badge
            rounded
            color="blue"
            label="Renewal Pending"
            v-if="!props.row.is_completed && props.row.is_payment && props.row.is_approved"
          />
          <q-badge
            rounded
            color="green"
            label="Renewal Completed"
            v-else-if="props.row.is_completed && props.row.is_payment && props.row.is_approved"
          />
          &nbsp;
          <q-badge
            rounded
            color="green"
            label="Payment Completed"
            v-if="props.row.is_payment"
          />
          <q-badge
            rounded
            color="yellow"
            text-color="black"
            label="Payment Pending"
            v-else
          />
          &nbsp;
          <q-badge
            rounded
            color="green"
            label="Admin approved"
            v-if="props.row.is_approved == true"
          />
          <q-badge
            rounded
            color="blue"
            label="Waiting admin approval"
            v-if="!props.row.is_approved"
          />
        </q-td>
        <q-td auto-width>
          <span
            v-if="
              props.row.is_payment
            "
            >Payment completed</span
          >
          <q-btn
            color="primary"
            size="sm"
            v-else
            @click="completePayment(props.row.id)"
            >Confirm Payment</q-btn
          >
        </q-td>
        <q-td auto-width>
          <span v-if="props.row.is_approved">Admin approved</span>
          <q-btn
            color="primary"
            size="sm"
            v-else
            @click="adminApprove(props.row.id)"
            >Approve renewal</q-btn
          >
        </q-td>
        <q-td auto-width> {{ new Date(props.row.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }} </q-td>
        <q-td auto-width>
          <a
            v-if="props.row.is_completed"
            :href="`/dashboard/renewal/${props.row.id}`"
            >Show Details</a
          >
          <span
            v-else-if="
              props.row.is_approved && props.row.is_payment && !props.row.is_completed
            "
            ><q-btn
              color="primary"
              size="sm"
              @click="completeRenewalPhase(props.row.id)"
            >
              <div>Complete Renewal Phase</div>
            </q-btn></span
          >
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
    name: "payment",
    required: true,
    label: "Payment",
    align: "left",
    field: "payment",
    sortable: true,
  },
  {
    name: "approval",
    required: true,
    label: "Admin Approval",
    align: "left",
    field: "approval",
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
import {
  completeRenewal,
  completePayment,
  adminApprove,
} from "src/api/payment";
export default {
  name: "MonitorListTable",
  props: ["data", "details"],
  methods: {
    processNumber(number) {
      return number.replace(/\//g, "_");
    },
    completeRenewalPhase(id) {
      completeRenewal(id)
        .then((res) => {
          console.log(res);
          this.$router.go();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    completePayment(id) {
      completePayment(id)
        .then((res) => {
          console.log(res);
          this.$router.go();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    adminApprove(id) {
      adminApprove(id)
        .then((res) => {
          console.log(res);
          this.$router.go();
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },

  setup() {

    return {
      columns,
      loading: ref(true),
      iyzicoDialog: ref(false),
      paymentPageUrl: ref(""),
    };
  },
};
</script>
