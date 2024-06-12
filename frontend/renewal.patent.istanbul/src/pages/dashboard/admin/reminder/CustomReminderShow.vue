<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              {{ reminder.ref_code }} {{ reminder.short_name }} Update Reminder
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="row">
                <div class="col-auto">
                  <div class="q-ma-md">
                    <p>You can activate/deactivate your Reminder.</p>
                    <q-toggle
                      v-model="activeStatusToggle"
                      :label="activeStatusToggle ? 'Active' : 'Inactive'"
                      left-label
                    />
                  </div>
                </div>
                <div class="col-auto">
                  <div class="q-ma-md">
                    <p>You can uninstall Reminder completely.</p>
                    <q-btn
                      color="primary"
                      label="Delete Reminder"
                      @click="this.deleteReminderDialog = true"
                    />
                  </div>
                </div>
              </div>
            </q-card-section>
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Update Reminder
              </div>
              <p class="text-body2 q-mb-none q-mt-md">
                You can update this reminder created earlier.
              </p>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <q-form ref="addReminderForm">
                <div class="row">
                  <div class="col-md-3 col-sm-12 w-100">
                    <div class="q-ma-md">
                      <q-select
                        v-model="type"
                        hint="Type"
                        :option-value="'value'"
                        :options="reminderOptions"
                        placeholder="Priority Research"
                      />
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-12 w-100">
                    <div class="q-ma-md">
                      <q-input
                        v-model="refCode"
                        hint="Reference Code"
                        placeholder="RefCode.001-72"
                      />
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-12 w-100">
                    <div class="q-ma-md">
                      <q-input
                        v-model="shortName"
                        hint="Short Name"
                        placeholder="Mixing Bowl"
                      />
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-12 w-100">
                    <div class="q-ma-md">
                      <q-input
                        v-model="note"
                        hint="Note"
                        placeholder="Example: This is a note."
                        counter
                        maxlength="60"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12">
                  <div class="q-pa-md">
                    <h5 class="text-h5 q-my-none q-mb-md">Reminder Settings</h5>
                    <div class="row">
                      <div class="col-md-4 col-sm-12">
                        <div class="q-ma-sm">
                          <q-toggle
                            v-model="sendEmailToggle"
                            color="primary"
                            icon="mail"
                            label="Send e-mail when it's time for Reminder."
                          />
                          <p class="text-body2">
                            If you activate the option, when the reminder time
                            comes, you will receive a notification about the
                            reminder by mail.
                          </p>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-12">
                        <div class="q-ma-sm">
                          <q-toggle
                            v-model="sendCalendarAlertToggle"
                            color="primary"
                            icon="alarm"
                            label="Receive alarms for this reminder if you have subscribed to the calendar."
                          />
                          <p class="text-body2">
                            If you activate the option and you have subscribed
                            to our system with the calendar application you use,
                            your calendar application will notify you with
                            alarms.
                          </p>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-12">
                        <div class="q-ma-sm">
                          <q-toggle
                            v-model="sendOnHolidayToggle"
                            color="primary"
                            label="Send notification on holidays."
                          />
                          <p class="text-body2">
                            If you deactivate the option, the notification will
                            be sent the nearest first business day after the
                            reminder date.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row q-ma-md q-mt-none">
                  <div class="col-12">
                    <h5 class="text-h5 q-mt-sm q-mb-sm">Reminder Dates</h5>
                  </div>
                  <div class="col-auto">
                    <div class="q-my-md">
                      <p class="text-body2">
                        You can add or remove a specific date.
                      </p>
                      <q-input
                        v-model="customDate"
                        mask="date"
                        :rules="['date']"
                        hint="Custom Date"
                        :placeholder="`Example : ` + now"
                      >
                        <template v-slot:prepend>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              cover
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="customDate"
                                :options="customDateFn"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Close"
                                    color="primary"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                        <template v-slot:append>
                          <q-btn
                            round
                            dense
                            flat
                            icon="add"
                            @click="addCustomDate"
                          />
                        </template>
                      </q-input>
                    </div>
                  </div>
                  <div class="col-12">
                    <q-list bordered separator v-if="reminderDates.length > 0">
                      <q-item
                        clickable
                        v-ripple
                        v-for="(fragment, index) in reminder.fragments"
                      >
                        <q-item-section top side>
                          <div class="text-grey-8 q-gutter-xs">
                            <q-btn
                              class="gt-xs"
                              size="12px"
                              flat
                              dense
                              round
                              icon="delete"
                              @click="
                                openDeleteDialog(reminder.id, fragment.id)
                              "
                              :disabled="user.email == email"
                            />
                          </div>
                        </q-item-section>
                        <q-item-section v-if="fragment.repeat == 'continuous'">
                          <q-item-label overline
                            >Reminder Date - Until Dismiss</q-item-label
                          >
                          <q-item-label>{{ fragment.start_date }}</q-item-label>
                          <q-item-label
                            class="bg-yellow-2"
                            v-if="time == 'Days'"
                            >This is a continuous reminder. the reminder will
                            continue every {{ frequency }} Days or until
                            dismissed..</q-item-label
                          >
                          <q-item-label class="bg-yellow-2" v-else>
                            <div>
                              This is a continuous reminder. the reminder will
                              continue every {{ frequency }} {{ time.label }} or
                              until dismissed..
                            </div>
                          </q-item-label>
                        </q-item-section>
                        <q-item-section v-else>
                          <q-item-label overline
                            >{{ index + 1 }}. Reminder Date
                            <span
                              v-if="
                                index == Object.keys(reminderDates).length - 1
                              "
                              >, Last Reminder</span
                            ></q-item-label
                          >
                          <q-item-label>{{ fragment.start_date }}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </q-list>
                    <q-list bordered separator v-else>
                      <q-item clickable v-ripple>
                        <q-item-section>
                          <q-item-label>Empty</q-item-label>
                          <q-item-label
                            >A reminder date has not yet been
                            created.</q-item-label
                          >
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="q-ma-md">
                      <h5 class="text-h5 q-my-none q-mb-md">
                        Recipient Email Addresses
                      </h5>
                      <p class="text-body2">
                        A notification will be sent to each e-mail address you
                        add when the time comes.
                      </p>
                      <q-input
                        v-model="email"
                        hint="Email addresses to be reminded"
                        placeholder="Example: admin@patent.istanbul"
                      >
                        <template v-slot:prepend>
                          <q-icon name="email" />
                        </template>
                        <template v-slot:append>
                          <q-btn
                            round
                            dense
                            flat
                            icon="add"
                            @click="addEmail"
                          />
                        </template>
                      </q-input>
                    </div>
                  </div>
                </div>
                <!-- Mail Address -->
                <div class="row" v-if="emails.length > 0">
                  <div class="col">
                    <div class="q-ma-md">
                      <q-list bordered class="rounded-borders">
                        <q-item-label header
                          >Recipient Mail Addresses</q-item-label
                        >

                        <q-item v-for="(email, index) in emails">
                          <q-item-section top side>
                            <div class="text-grey-8 q-gutter-xs">
                              <q-btn
                                class="gt-xs"
                                size="12px"
                                flat
                                dense
                                round
                                icon="delete"
                                @click="emails.splice(index, 1)"
                                :disabled="user.email == email"
                              />
                            </div>
                          </q-item-section>
                          <q-item-section top class="col-2 gt-sm">
                            <q-item-label class="q-mt-sm text-left">{{
                              email
                            }}</q-item-label>
                          </q-item-section>
                        </q-item>
                      </q-list>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="q-ma-md">
                      <h5 class="text-h5 q-my-none q-mb-md">Teams</h5>
                      <p class="text-body2">
                        The teams you will add will also be able to follow and
                        edit the reminder from the interface.
                      </p>
                      <q-select
                        bottom-slots
                        :options="teams"
                        v-model="selectedTeam"
                        :option-value="'id'"
                        :option-label="'name'"
                        label="Team Name"
                        counter
                        maxlength="12"
                        dense="dense"
                      >
                        <template v-slot:before>
                          <q-icon name="event" />
                        </template>

                        <template v-slot:hint> Select team </template>

                        <template v-slot:append>
                          <q-btn
                            round
                            dense
                            flat
                            icon="add"
                            @click="addTeams"
                          />
                        </template>
                      </q-select>
                    </div>
                  </div>
                </div>
                <!-- Mail Address -->
                <div class="row" v-if="teammates.length > 0">
                  <div class="col">
                    <div class="q-ma-md">
                      <q-list bordered class="rounded-borders">
                        <q-item-label header>Teams</q-item-label>

                        <q-item v-for="(teammate, index) in teammates">
                          <q-item-section top side>
                            <div class="text-grey-8 q-gutter-xs">
                              <q-btn
                                class="gt-xs"
                                size="12px"
                                flat
                                dense
                                round
                                icon="delete"
                                @click="teammates.splice(index, 1)"
                              />
                            </div>
                          </q-item-section>
                          <q-item-section top class="col-2 gt-sm">
                            <q-item-label class="q-mt-sm">{{
                              teammate.name
                            }}</q-item-label>
                          </q-item-section>
                        </q-item>
                      </q-list>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="q-ma-md">
                      <q-btn
                        color="primary"
                        label="Update Reminder"
                        @click="updateReminder"
                      />
                    </div>
                  </div>
                </div>
              </q-form>
            </q-card-section>
          </q-card>
        </div>
      </div>
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
              color="primary"
              v-close-popup
              @click="deleteReminderFragment"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <q-dialog v-model="deleteReminderDialog" persistent>
        <q-card>
          <q-card-section class="row items-center">
            <div class="text-h6 text-weight-bold text-primary">
              Delete Reminder
            </div>
          </q-card-section>
          <q-card-section>
            <p>This action cannot be undone.</p>
            <p>Are you sure you want to delete this reminder?</p>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="Cancel" color="primary" v-close-popup />
            <q-btn
              label="Delete"
              color="primary"
              v-close-popup
              @click="deleteReminder(reminder.id)"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import {
  getReminder,
  deleteReminder,
  updateReminderStatus,
  deleteReminderFragment,
  addReminderFragment,
} from "src/api/admin/reminders";
import {
  updateReminder,
  getReminderDates,
  getFields,
} from "src/api/admin/custom-reminders";
import { getTeams } from "src/api/admin/team";
import { useAuthStore } from "src/stores/useAuthStore";
export default {
  components: {},
  name: "IndexPage",
  methods: {
    deleteReminder(id) {
      deleteReminder(id).then((res) => {
        this.$router.push("/dashboard/admin/reminders");
      });
    },
    updateReminderStatus(status) {
      const is_active = status === "active" ? 1 : 0;
      updateReminderStatus(this.reminder.id, { status: "update", is_active })
        .then((res) => {
          this.currentStatus = res.status;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    openDeleteDialog(reminder_id, fragment_id) {
      this.deleteFragmentDialog = true;
      this.reminder_id = reminder_id;
      this.fragment_id = fragment_id;
    },
    deleteReminderFragment() {
      deleteReminderFragment(this.reminder_id, this.fragment_id)
        .then((res) => {
          this.reminder.fragments = res.fragments;
          this.$q.notify({
            color: "primary",
            message: res.message,
          });
        })
        .catch((err) => {
          console.log(err);
          this.$q.notify({
            color: "negative",
            message: "Reminder could not be deleted",
          });
        });
    },
    addCustomDate() {
      if (this.customDate == "") {
        return;
      }
      this.customDate = this.customDate.replace(/\//g, "-");

      // this.customDate = "";
      addReminderFragment(this.reminder.id, {
        start_date: this.customDate + " 09:00:00",
        end_date: this.customDate + " 09:15:00",
        repeat: false,
      })
        .then((res) => {
          this.$q.notify({
            message: res.message,
            color: "primary",
            icon: "check",
          });
          this.reminderDates.push(res.fragment);

          this.reminderDates.sort((a, b) => {
            if (a.start_date < b.start_date) {
              return -1;
            }
            if (a.start_date > b.start_date) {
              return 1;
            }
            return 0;
          });
        })
        .catch((err) => {
          this.$q.notify({
            message: "Reminder could not be added",
            color: "negative",
            icon: "close",
          });
        });
    },
    addEmail() {
      if (this.emails.includes(this.email)) {
        return;
      }
      this.emails.push(this.email);
      this.email = "";
    },
    addTeams() {
      // eğer this.teammates içerisinde this.selectedTeam.name bulunuyorsa ekle
      if (!this.teammates.includes(this.selectedTeam)) {
        this.teammates.push(this.selectedTeam);
      }

      // this.selectedTeam içerisindeki her bir array'ın users'lerinin email lerini this.emails içerisine ekle
      this.selectedTeam.users.forEach((user) => {
        if (!this.emails.includes(user.email)) {
          this.emails.push(user.email);
        }
      });
    },
    updateReminder() {
      let form = {
        refCode: this.refCode,
        shortName: this.shortName,
        type: this.type,
        deadline: this.deadline,
        startDate: this.startDate,
        endDate: this.endDate,
        reminderTab: this.reminderTab,
        frequency: this.frequency,
        frequencyType: this.time,
        time: this.time,
        panel: this.panel,
        sinceFrom: this.sinceFrom,
        repetition: this.repetition,
        teams: this.teammates,
        note: this.note,
        sendEmail: this.sendEmailToggle,
        sendCalendarAlert: this.sendCalendarAlertToggle,
        sendOnHoliday: this.sendOnHolidayToggle,
        emails: this.emails,
        specificDates: this.specificDates,
        is_active: this.activeStatusToggle,
      };
      updateReminder(this.reminder.id, form)
        .then((res) => {
          if (res.status == 200) {
            this.$q.notify({
              message: res.message,
              color: "primary",
              icon: "check",
            });
          } else {
            this.$q.notify({
              message: "Reminder could not be added",
              color: "negative",
              icon: "close",
            });
          }
        })
        .catch((err) => {
          this.$q.notify({
            message: "Reminder could not be added",
            color: "negative",
            icon: "close",
          });
        });
    },
  },
  setup() {
    const router = useRouter();
    const reminder = ref([]);
    const currentStatus = ref(null);
    const updateModel = ref(null);
    const now = new Date().toISOString().substr(0, 10).replace(/-/g, "/");
    const emails = ref([]);
    const teammates = ref([]);
    const teams = ref([]);
    const user = useAuthStore().user;

    const refCode = ref("");
    const shortName = ref("");
    const reminderType = ref("");
    const deadline = ref("");
    const startDate = ref("");
    const endDate = ref("");
    const frequency = ref(null);
    const reminderDates = ref([]);
    const note = ref("");
    const sendEmailToggle = ref(true);
    const sendCalendarAlertToggle = ref(true);
    const sendOnHolidayToggle = ref(true);
    const type = ref({
      label: "Select Reminder Type",
      value: "not-selected",
    });
    const activeStatusToggle = ref(true);
    onMounted(() => {
      const id = router.currentRoute.value.params.id;
      getReminder(id).then((res) => {
        reminder.value = res;
        currentStatus.value = res.status;
        updateModel.value = res.is_active ? "active" : "inactive";
        refCode.value = res.ref_code;
        shortName.value = res.short_name;
        reminderType.value = res.reminder_type;
        deadline.value = res.deadline;
        startDate.value = res.start_date;
        endDate.value = res.deadline;
        frequency.value = res.frequency;
        reminderDates.value = res.fragments;
        emails.value = res.emails;
        teammates.value = res.teams;
        note.value = res.note;
        sendEmailToggle.value = res.send_reminder_email == 1 ? true : false;
        sendCalendarAlertToggle.value =
          res.use_reminder_alarm == 1 ? true : false;
        sendOnHolidayToggle.value =
          res.send_notification_on_holiday == 1 ? true : false;
        type.value.label = JSON.parse(res.type).label;
        activeStatusToggle.value = res.is_active == 1 ? true : false;
      });
      emails.value.push(useAuthStore().user.email);
      getTeams()
        .then((res) => {
          teams.value = res;
        })
        .catch((err) => {
          console.log(err);
        });

    });

    return {
      deleteReminderDialog: ref(false),
      activeStatusToggle,
      now: now,
      customDate: ref(""),
      customDateFn(customDate) {
        return customDate >= now;
      },
      note,
      deleteFragmentDialog: ref(false),
      reminder,
      currentStatus,
      updateModel,
      updateOptions: [
        {
          label: "Active",
          value: "active",
        },
        {
          label: "Inactive",
          value: "inactive",
        },
      ],
      confirm: ref(false),
      user,
      reminderDates,
      reminderOptions: [
        {
          label: "TÜRKPATENT Marka Başvurusu - Red İtiraz",
          value: "marka-basvurusu-red-itiraz",
          repeat: false,
        },
        {
          label: "TÜRKPATENT Marka Başvurusu - Yayından İnme",
          value: "marka-basvurusu-yayindan-inme",
          repeat: false,
        },
        {
          label: "TÜRKPATENT Marka Başvurusu - Tescil Harcının Ödenmesi",
          value: "marka-basvurusu-tescil-harcinin-odenmesi",
          repeat: false,
        },
        {
          label: "TÜRKPATENT Vekillik Yenileme",
          value: "vekillik-yenileme",
          repeat: true,
        },
        {
          label: "Patent - Kullanım Son Tarihi",
          value: "kullanim-son-tarihi",
          repeat: false,
        },
        {
          label: "Custom Reminder",
          value: "custom-reminder",
          repeat: true,
        },
      ],
      refCode,
      shortName,
      reminderType,
      type,
      emails,
      teammates,
      email: ref(""),
      showReminderDates: ref(true),
      sendEmailToggle,
      sendCalendarAlertToggle,
      sendOnHolidayToggle,
      selectedTeam: ref(null),
      teams,
    };
  },
};
</script>
<style scoped>
@media screen and (max-width: 600px) {
  .w-100{
    width: 100% !important;
  }
}
</style>
