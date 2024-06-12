<template>
  <q-table
    flat
    bordered
    title="Team Members"
    :rows="team.users"
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
          {{ props.row.phone ?? 'Empty' }}
        </q-td>
        <q-td auto-width>
          <q-btn
            color="primary"
            flat
            @click="removeTeamMember(props.row.id)"
            label="Remove Team Member"
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
    name: "details",
    required: true,
    label: "Details",
    align: "left",
    field: "Details",
    sortable: true,
  },
];
import { ref, onMounted } from "vue";
import { removeMemberToTeam } from "src/api/admin/team";
export default {
  name: "TeamListTable",
  props: ["team"],
  methods: {
    removeTeamMember(id) {
      console.log(id)
      removeMemberToTeam(this.team.id, id).then((res) => {
        this.$q.notify({
          color: "green-4",
          textColor: "white",
          icon: "cloud_done",
          message: "Team member removed successfully",
        });
        this.$router.go();
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
