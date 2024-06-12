<template>
  <q-table
    flat
    bordered
    title="Users"
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
      <q-tr :props="props">
        <q-td auto-width>
          {{ props.row.id }}
        </q-td>
        <q-td auto-width>
          {{ props.row.name }}
        </q-td>
        <q-td auto-width>
          {{ props.row.email }}
        </q-td>
        <q-td auto-width>
          {{ props.row.phone }}
        </q-td>
        <q-td auto-width>
          {{ props.row.renewals.length }}
        </q-td>
        <q-td auto-width>
          {{ new Date(props.row.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
        </q-td>
        <q-td auto-width>
          <span v-if="props.row.is_verified == 1">Verified</span>
          <span v-else>Unverified</span>
        </q-td>
        <q-td auto-width>
          <q-btn
            color="primary"
            @click="details(props.row.id)"
            label="Details"
          />
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script>
const columns = [
  {
    name: "id",
    required: true,
    label: "#",
    align: "left",
    field: (row) => row.type,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "name",
    required: true,
    label: "Name",
    align: "left",
    field: "name",
    sortable: true,
  },
  {
    name: "email",
    required: true,
    label: "Email",
    align: "left",
    field: "email",
    sortable: true,
  },
  {
    name: "phone",
    required: true,
    label: "Phone",
    align: "left",
    field: "phone",
    sortable: true,
  },
  {
    name: "renewals",
    required: true,
    label: "Renewals",
    align: "left",
    field: "renewals",
    sortable: true,
  },
  {
    name: "created_at",
    required: true,
    label: "Registered At",
    align: "left",
    field: "created_at",
    sortable: true,
  },
  {
    name: "is_verified",
    required: true,
    label: "Verified",
    align: "left",
    field: "is_verified",
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
import { getUser } from "src/api/admin/user";
export default {
  name: "UserListTable",
  props: ["data", "details"],
  methods: {
    processNumber(number) {
      return number.replace(/\//g, "_");
    },
    details(id){
        this.$router.push({ name: "AdminUserDetails", params: { id: id } });
    }
  },

  setup(props) {
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
      formatDate,
    };
  },
};
</script>

<style scoped>
.my-custom-toggle {
  border: 1px solid #027be3;
}
</style>
