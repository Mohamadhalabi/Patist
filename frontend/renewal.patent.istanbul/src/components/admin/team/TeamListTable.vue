<template>
  <q-table
    flat
    bordered
    title="Teams"
    :rows="teams"
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
          {{ props.row.users.length }}
        </q-td>
        <q-td auto-width>
          <q-btn
            color="primary"
            flat
            @click="details(props.row.id)"
            label="Team Details"
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
    name: "members",
    required: true,
    label: "Members",
    align: "left",
    field: "members",
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
import { ref, onMounted } from "vue";
export default {
  name: "TeamListTable",
  props: ["teams"],
  methods: {
    details(id) {
      this.$router.push({
          name: "TeamShow",
          params: { id: id },
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
