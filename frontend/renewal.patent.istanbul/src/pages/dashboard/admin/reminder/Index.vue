<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Reminders
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <div class="col-md-3 col-sm-12 w-100">
            <q-card flat bordered class="monitor-card q-mr-md q-mb-md">
            <q-card-section> </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">
                There are {{ reminderList.length }} active reminders.
              </p>
            </q-card-section>
          </q-card>
          </div>
          <q-card flat bordered class="monitor-card col-md-6 col-sm-12 w-100">
            <q-card-section> </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">
                You can import the calendar using the link below to keep track
                of your notifications instantly.
              </p>
              <div class="row">
                <div class="col-auto">
                  <div class="truncate q-ma-md" style="margin-left: 0">
                    {{ calendar.link }}
                  </div>
                </div>
                <div class="col-auto self-center">
                  <q-btn
                    negative
                    @click="copy(calendar.link)"
                    icon="content_paste"
                    unelevated
                    text-color="primary"
                    style="width: 100%"
                  />
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md">
          <ReminderListTable v-if="reminderListLoaded" :data="reminderList" />
        </div>
        <!-- <div class="q-pa-md">
          <CustomReminderListTable v-if="customReminderListLoaded" :data="customReminderList" />
        </div> -->
      </div>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import ReminderListTable from "src/components/admin/reminder/ReminderListTable.vue";
import { reminders, getCalendar } from "src/api/admin/reminders";
import { copyToClipboard } from "quasar";

export default {
  components: { ReminderListTable },
  name: "IndexAdminReminder",
  methods: {
    copy(text) {
      copyToClipboard(text)
        .then(() => {
          this.$q.notify({
            color: "positive",
            message: "Copied to clipboard.",
          });
        })
        .catch(() => {
          this.$q.notify({
            color: "negative",
            message: "Could not copy to clipboard.",
          });
        });
    },
    refreshPage() {
      this.$router.go();
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
    const reminderListLoaded = ref(false);
    const reminderList = ref([]);
    const calendar = ref([]);

    onMounted(() => {
      getCalendar().then((res) => {
        calendar.value = res;
      });
      reminders().then((res) => {
        reminderList.value = res;
        reminderListLoaded.value = true;
      });
    });
    const reminder_id = ref(null);
    const fragment_id = ref(null);
    return {
      deleteFragmentDialog: ref(false),
      calendar,
      reminderList,
      reminderListLoaded,
    };
  },
};
</script>
<style scoped>
.truncate{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
@media screen and (max-width: 600px) {
  .w-100{
    width: 100% !important;
  }
}
</style>
