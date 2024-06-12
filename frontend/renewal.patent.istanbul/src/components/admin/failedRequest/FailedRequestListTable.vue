<template>
  <q-table
    flat
    bordered
    title="Failed Requests"
    :rows="requests"
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
          {{ props.row.number }}
        </q-td>
        <q-td auto-width>
          {{ props.row.user.email }}
        </q-td>
        <q-td auto-width>
          {{ new Date(props.row.created_at).toLocaleString('tr-TR', {timeZoneName:'short', year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit'}).replace(',', '') }}
        </q-td>
        <q-td auto-width>
          <q-btn
            color="primary"
            flat
            @click="deleteRequest(props.row.id)"
            label="Delete"
            size="sm"
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
    name: "user",
    required: true,
    label: "User",
    align: "left",
    field: "user",
    sortable: true,
  },
  {
    name: "date",
    required: true,
    label: "Error Time",
    align: "left",
    field: "date",
    sortable: true,
  },
  {
    name: "action",
    required: true,
    label: "Action",
    align: "left",
    field: "action",
    sortable: true,
  },
];
import { ref, onMounted } from "vue";
import { deleteFailedRequest } from "src/api/failed-request";
import { Router } from "vue-router";
export default {
  name: "FailedRequestListTable",
  props: ["requests"],
  methods: {
    deleteRequest(id) {
      deleteFailedRequest(id)
        .then((res) => {
          this.$q.notify({
            message: res.message,
            color: "primary",
            icon: "check",
          });
          this.$router.go();
        })
        .catch((err) => {
          this.$q.notify({
            message: err.message,
            color: "negative",
            icon: "check",
          });
        });
    },
  },
  setup(props) {
    return {
      columns,
    };
  },
};
</script>
