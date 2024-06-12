<template>
  <q-table
    flat
    bordered
    title="Reminders"
    :rows="this.data"
    :columns="columns"
    :filter="filter"
    row-key="id"
    :pagination.sync="pagination"
  >
    <template v-slot:loading>
      <q-inner-loading showing color="primary" />
    </template>

    <template v-slot:top-right>
      <q-input
        borderless
        dense
        debounce="300"
        v-model="filter"
        placeholder="Search"
      >
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </template>

    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.label }}
        </q-th>
      </q-tr>
    </template>

    <template v-slot:body="props">
      <q-tr :props="props" :class="props.row.is_active ? '' : 'bg-grey-3'">
        <q-td auto-width>
          <q-btn
            size="sm"
            color="primary"
            round
            dense
            @click="props.expand = !props.expand"
            :icon="props.expand ? 'remove' : 'add'"
          />
        </q-td>
        <q-td auto-width>
          {{ JSON.parse(props.row.type).label }}
        </q-td>
        <q-td auto-width>
          {{ props.row.ref_code }} {{ props.row.short_name }}
        </q-td>

        <q-td auto-width>
          <strong>{{ props.row.is_active ? "Active" : "Inactive" }}</strong>
        </q-td>
        <q-td auto-width>
          {{ props.row.deadline }}
        </q-td>
        <q-td auto-width>
          {{
            props.row.note
          }}
        </q-td>
        <q-td auto-width>
          <q-btn
            color="primary"
            @click="details(props.row.id, props.row.type)"
            label="Details"
          />
        </q-td>
      </q-tr>
      <q-tr v-show="props.expand" :props="props">
        <q-td colspan="100%">
          <q-list padding>
            <q-item-label header>Reminder Fragments</q-item-label>

            <q-item
              tag="label"
              v-for="(fragment, index) in props.row.fragments"
            >
              <q-item-section
                side
                top
                class="text-center self-center"
                style="width: 120px"
              >
                <q-btn
                  label="Delete"
                  color="negative"
                  width="100%"
                  size="sm"
                  @click="openDeleteDialog(props.row.id, fragment.id)"
                />
              </q-item-section>

              <q-item-section
                side
                top
                class="text-center self-center"
                style="width: 120px"
              >
                <span v-if="fragment.is_completed">Completed</span>
                <span v-else>Waiting</span>
              </q-item-section>

              <q-item-section>
                <q-item-label>{{ fragment.title }}</q-item-label>
                <q-item-label caption>
                  <strong>Reminder Date :</strong> {{ fragment.start_date }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-td>
      </q-tr>
    </template>
  </q-table>
  <q-dialog v-model="deleteFragmentDialog" persistent>
    <q-card>
      <q-card-section class="row items-center">
        <strong>
          <span>Delete Reminder</span>
        </strong>
      </q-card-section>
      <q-card-section class="row items-center">
        <span class="q-ml-sm"
          >Are you sure you want to delete this reminder?</span
        >
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Cancel" color="primary" v-close-popup />
        <q-btn
          label="Delete"
          color="negative"
          v-close-popup
          @click="deleteReminderFragment"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
const columns = [
  {
    name: "id",
    required: true,
    label: "",
    align: "left",
    field: (row) => row.ref_code,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "type",
    required: true,
    label: "Type",
    align: "left",
    field: (row) => row.type,
    sortable: true,
  },
  {
    name: "id",
    required: true,
    label: "Ref Code - Short Name",
    align: "left",
  },
  {
    name: "status",
    required: true,
    label: "Status",
    align: "left",
    field: (row) => row.status,
    sortable: true,
  },
  {
    name: "deadline",
    required: true,
    label: "Deadline",
    align: "left",
    field: (row) => row.deadline,
    sortable: true,
  },
  {
    name: "note",
    required: true,
    label: "Note",
    align: "left",
    field: (row) => row.note,
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
import { deleteReminderFragment } from "src/api/admin/reminders";
export default {
  name: "UserListTable",
  props: ["data", "details"],
  methods: {
    processNumber(number) {
      return number.replace(/\//g, "_");
    },
    details(id, type) {
      let reminder_type = JSON.parse(type);
      let name =
        reminder_type.value == "patent-renewal"
          ? "AdminReminderDetails"
          : "AdminCustomReminderDetails";
      this.$router.push({ name: name, params: { id: id } });
    },
    openDeleteDialog(reminder_id, fragment_id) {
      this.deleteFragmentDialog = true;
      this.reminder_id = reminder_id;
      this.fragment_id = fragment_id;
    },
    deleteReminderFragment() {
      deleteReminderFragment(this.reminder_id, this.fragment_id)
        .then((res) => {
          this.$q.notify({
            color: "positive",
            message: res.message,
          });

          this.data.forEach((reminder) => {
            if (reminder.id == this.reminder_id) {
              reminder.fragments.forEach((fragment) => {
                if (fragment.id == this.fragment_id) {
                  reminder.fragments.splice(
                    reminder.fragments.indexOf(fragment),
                    1
                  );
                }
              });
            }
          });
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            message: "Reminder fragment could not be deleted.",
          });
        });
    },
  },
  setup() {
    const reminder_id = ref(null);
    const fragment_id = ref(null);
    return {
      deleteFragmentDialog: ref(false),
      columns,
      loading: ref(true),
      filter: ref(""),
      pagination: {
        rowsPerPage: 15,
      },
      checkTrue: ref(false),
      checkFalse: ref(false),
    };
  },
};
</script>

<style scoped>
.my-custom-toggle {
  border: 1px solid #027be3;
}
</style>
