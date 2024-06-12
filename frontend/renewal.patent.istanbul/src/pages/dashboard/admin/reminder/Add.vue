<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Custom Reminder
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                New Custom Reminder
              </div>
              <p class="text-body2 q-mb-none q-mt-md">
                You can create a special reminder by configuring the reminder
                from the fields below.
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
                        v-model="deadline"
                        mask="date"
                        hint="Deadline"
                        :placeholder="`Example : ` + now"
                      >
                        <template v-slot:prepend>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              cover
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date v-model="deadline" :options="deadlineFn">
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
                      </q-input>
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
                  <div class="col-md-3 col-sm-12">
                    <div class="q-ma-md">
                      <q-input
                        v-model="shortName"
                        hint="Short Name"
                        placeholder="Mixing Bowl"
                      />
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12">
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
                <div class="col-md-4 col-sm-12">
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

                <div class="row" v-if="type.value != 'not-selected'">
                  <div class="q-pa-md col-12">
                    <div>
                      <h5 class="text-h5 q-my-none q-mb-md">
                        {{ type.label }}
                      </h5>
                    </div>
                    <div>
                      <div>
                        <div class="text-h6">Reminder settings</div>
                        <div class="q-mt-sm">
                          <p class="text-body2">
                            You can customize the reminder you created as you
                            wish.
                          </p>
                        </div>
                        <div class="row">
                          <div class="col-md-auto col-sm-12 self-center w-100">
                            <q-select
                              v-model="reminderTab"
                              inline
                              hint="Repeat type"
                              :option-value="'value'"
                              :option-label="'label'"
                              :options="[
                                { label: 'Repeat every', value: 'repeat' },
                                { label: 'One time', value: 'one-time' },
                              ]"
                              align="justify"
                            />
                          </div>
                          <div
                            class="col-md-auto col-sm-12 w-100"
                            v-if="reminderTab.value == 'one-time'"
                          >
                            <div class="q-ma-md">
                              <q-input
                                v-model="startDate"
                                mask="date"
                                :rules="['date']"
                                hint="Start Date"
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
                                        v-model="startDate"
                                        :options="startDateFn"
                                      >
                                        <div
                                          class="row items-center justify-end"
                                        >
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
                              </q-input>
                            </div>
                          </div>
                          <div
                            class="col-md-auto col-sm-12 self-center w-100"
                            v-if="reminderTab.value == 'repeat'"
                          >
                            <div class="q-ma-md">
                              <q-input
                                v-model="frequency"
                                mask="date"
                                :placeholder="'Example: 7'"
                                hint="Frequency"
                                input-class="text-center"
                              >
                              </q-input>
                            </div>
                          </div>
                          <div
                            class="col-md-2 col-sm-12 self-center w-100"
                            v-if="reminderTab.value == 'repeat'"
                          >
                            <q-select
                              v-model="time"
                              inline
                              :option-value="'value'"
                              :option-label="'label'"
                              :options="[
                                { label: 'Days', value: 'days' },
                                {
                                  label: 'Business Days',
                                  value: 'business-days',
                                },
                                { label: 'Weeks', value: 'weeks' },
                                { label: 'Months', value: 'months' },
                              ]"
                              hint="Please choose time type"
                            />
                          </div>
                        </div>
                        <div v-if="reminderTab.value == 'repeat'">
                          <div class="row">
                            <div class="col-md-auto col-sm-12 w-100">
                              <div>
                                <q-select
                                  v-model="panel"
                                  inline
                                  hint="Time interval of the reminder"
                                  :option-value="'value'"
                                  :option-label="'label'"
                                  :options="[
                                    { label: 'Until to', value: 'until-to' },
                                    {
                                      label: 'Since from',
                                      value: 'since-from',
                                    },
                                    { label: 'Between', value: 'between' },
                                    {
                                      label: 'Until dismissed',
                                      value: 'until-dismissed',
                                    },
                                  ]"
                                  align="justify"
                                />
                              </div>
                            </div>
                            <div class="col-md-auto col-sm-12 w-100">
                              <div v-if="panel.value == 'until-to'">
                                <div class="row q-mx-md">
                                  <div class="col-md-auto col-sm-12 self-center w-100">
                                    <div>
                                      <q-input
                                        v-model="endDate"
                                        mask="date"
                                        :rules="['date']"
                                        hint="End Date"
                                        :placeholder="`Example : ` + now"
                                      >
                                        <template v-slot:prepend>
                                          <q-icon
                                            name="event"
                                            class="cursor-pointer"
                                          >
                                            <q-popup-proxy
                                              cover
                                              transition-show="scale"
                                              transition-hide="scale"
                                            >
                                              <q-date
                                                v-model="endDate"
                                                :options="endDateFn"
                                              >
                                                <div
                                                  class="row items-center justify-end"
                                                >
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
                                      </q-input>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div v-if="panel.value == 'since-from'">
                                <div class="row q-mx-md">
                                  <div class="col-md-auto col-sm-12 self-center w-100">
                                    <q-input
                                      v-model="startDate"
                                      mask="date"
                                      :rules="['date']"
                                      hint="Start Date"
                                      :placeholder="`Example : ` + now"
                                    >
                                      <template v-slot:prepend>
                                        <q-icon
                                          name="event"
                                          class="cursor-pointer"
                                        >
                                          <q-popup-proxy
                                            cover
                                            transition-show="scale"
                                            transition-hide="scale"
                                          >
                                            <q-date
                                              v-model="startDate"
                                              :options="startDateFn"
                                            >
                                              <div
                                                class="row items-center justify-end"
                                              >
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
                                    </q-input>
                                  </div>
                                  <div class="col-md-auto col-sm-12 self-center w-100">
                                    <div
                                      v-if="
                                        sinceFrom.value != 'until-dismissed'
                                      "
                                    >
                                      <q-input
                                        v-model="repetition"
                                        hint="Repetition"
                                        :placeholder="'Example: 7'"
                                        type="number"
                                        class="q-mx-md"
                                      />
                                    </div>
                                  </div>
                                  <div class="col-md-auto col-sm-12 self-center w-100">
                                    <q-select
                                      class=""
                                      v-model="sinceFrom"
                                      :option-value="'value'"
                                      :options="sinceFromOptions"
                                      hint="Please choose time type"
                                    />
                                  </div>
                                </div>
                              </div>
                              <div v-if="panel.value == 'between'">
                                <div class="row q-mx-md">
                                  <div class="col-md-auto col-sm-12 self-center w-100">
                                    <q-input
                                      v-model="startDate"
                                      mask="date"
                                      :rules="['date']"
                                      hint="Start Date"
                                      :placeholder="`Example : ` + now"
                                    >
                                      <template v-slot:prepend>
                                        <q-icon
                                          name="event"
                                          class="cursor-pointer"
                                        >
                                          <q-popup-proxy
                                            cover
                                            transition-show="scale"
                                            transition-hide="scale"
                                          >
                                            <q-date
                                              v-model="startDate"
                                              :options="startDateFn"
                                            >
                                              <div
                                                class="row items-center justify-end"
                                              >
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
                                    </q-input>
                                  </div>
                                  <div class="self-center q-ma-md">
                                    <span class="q-ma-none q-pa-none">
                                      and
                                    </span>
                                  </div>
                                  <div class="col-md-auto col-sm-12 self-center w-100">
                                    <q-input
                                      v-model="endDate"
                                      mask="date"
                                      :rules="['date']"
                                      hint="End Date"
                                      :placeholder="`Example : ` + now"
                                    >
                                      <template v-slot:prepend>
                                        <q-icon
                                          name="event"
                                          class="cursor-pointer"
                                        >
                                          <q-popup-proxy
                                            cover
                                            transition-show="scale"
                                            transition-hide="scale"
                                          >
                                            <q-date
                                              v-model="endDate"
                                              :options="endDateFn"
                                            >
                                              <div
                                                class="row items-center justify-end"
                                              >
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
                                    </q-input>
                                  </div>
                                </div>
                              </div>
                              <div v-if="panel.value == 'until-dismissed'">
                                <div class="col-md-auto col-sm-12 self-center q-mx-md w-100">
                                  <q-input
                                    v-model="startDate"
                                    mask="date"
                                    :rules="['date']"
                                    hint="Start Date"
                                    :placeholder="`Example : ` + now"
                                  >
                                    <template v-slot:prepend>
                                      <q-icon
                                        name="event"
                                        class="cursor-pointer"
                                      >
                                        <q-popup-proxy
                                          cover
                                          transition-show="scale"
                                          transition-hide="scale"
                                        >
                                          <q-date
                                            v-model="startDate"
                                            :options="startDateFn"
                                          >
                                            <div
                                              class="row items-center justify-end"
                                            >
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
                                  </q-input>
                                </div>
                              </div>
                            </div>
                          </div>
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
                        v-for="(rDate, index) in reminderDates"
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
                                @click="removeCustomDate(index)"
                                :disabled="user.email == email"
                              />
                            </div>
                          </q-item-section>
                        <q-item-section v-if="rDate.repeat == 'continuous'">
                          <q-item-label overline
                            >Reminder Date - Until Dismiss</q-item-label
                          >
                          <q-item-label>{{ rDate.start_date }}</q-item-label>
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
                          <q-item-label>{{ rDate.start_date }}</q-item-label>
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
                        label="Add Reminder"
                        @click="addReminder"
                      />
                    </div>
                  </div>
                </div>
              </q-form>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>
<script>
import { ref, onMounted } from "vue";
import {
  addReminder,
  getReminderDates,
  getFields,
} from "src/api/admin/custom-reminders";
import { getTeams } from "src/api/admin/team";
import { useAuthStore } from "src/stores/useAuthStore";
export default {
  name: "AddAdminReminder",
  methods: {
    updateReminderDates() {
      if(this.ready != true){
        return;
      }
      let form = {
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
      };
      getReminderDates(form)
        .then((res) => {
          this.reminderDates = res || [];
        })
        .catch((err) => {
          console.log(err);
        });
    },
    addCustomDate(){
      if (this.customDate == "") {
        return;
      }
      this.customDate = this.customDate.replace(/\//g, "-");
      this.specificDates.push({
        date: this.customDate + " 09:00:00",
        type: "add",
      });
      this.reminderDates.push({
        title: "Custom Date",
        start_date: this.customDate + " 09:00:00",
        end_date: this.customDate + " 09:15:00",
        repeat: false,
      });
      console.log(this.specificDates)
      this.customDate = "";

      console.log(this.reminderDates)
      // this.reminderDates'i tek tek dolaş ve title'a göre sırala

      this.reminderDates.sort((a, b) => {
        if (a.start_date < b.start_date) {
          return -1;
        }
        if (a.start_date > b.start_date) {
          return 1;
        }
        return 0;
      });
    },
    removeCustomDate(index){
      this.specificDates.push({
        date: this.reminderDates[index].start_date,
        type: "remove",
      });
      this.reminderDates.splice(index, 1);
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
    addReminder() {
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
      };
      addReminder(form)
        .then((res) => {
          if (res.status == 200) {
            this.$q.notify({
              message: "Reminder added successfully",
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
  watch: {
    deadline(newVal, oldVal) {
      if (oldVal == "") {
        this.endDate = newVal;
      }
      this.updateReminderDates();
    },
    reminderTab(newVal, oldVal) {
      if (newVal.value != oldVal.value) {
        this.updateReminderDates();
      }
    },
    type(newVal, oldVal) {
      if (oldVal.value == "not-selected" && newVal.value != "custom-reminder") {
        let form = {
          type: newVal.value,
          deadline: this.deadline,
        };
        getFields(form).then((res) => {
          this.startDate = res.start_date;
          this.endDate = res.end_date;
          this.deadline = res.deadline;
          this.frequency = res.frequency;
          this.tab = res.tab;
          this.panel = res.panel;
          this.ready = true;
          // this.time = res.time;
          this.updateReminderDates();
        });
      }
    },
    endDate(newVal, oldVal) {
      this.updateReminderDates();
    },
    startDate(newVal, oldVal) {
      this.updateReminderDates();
    },
    repetition(newVal, oldVal) {
      this.updateReminderDates();
    },
    frequency(newVal, oldVal) {
      this.updateReminderDates();
    },
    time(newVal, oldVal) {
      if (newVal.value != oldVal.value) {
        this.updateReminderDates();
      }
    },
    tab(newVal, oldVal) {
      if (newVal.value != oldVal.value) {
        this.updateReminderDates();
      }
    },
    panel(newVal, oldVal) {
      if (newVal.value != oldVal.value) {
        this.updateReminderDates();
      }
    },
    between(newVal, oldVal) {
      if (newVal.value != oldVal.value) {
        this.updateReminderDates();
      }
    },
    sinceFrom(newVal, oldVal) {
      if (newVal.value != oldVal.value) {
        this.updateReminderDates();
      }
    },
  },
  setup() {
    const now = new Date().toISOString().substr(0, 10).replace(/-/g, "/");
    const emails = ref([]);
    const teammates = ref([]);
    const teams = ref([]);
    const user = useAuthStore().user;
    onMounted(() => {
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
      ready: ref(false),
      specificDates: ref([]),
      user,
      reminderDates: ref([]),
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
      refCode: ref(""),
      shortName: ref(""),
      note: ref(""),
      type: ref({
        label: "Select Reminder Type",
        value: "not-selected",
      }),
      frequency: ref(null),
      emails,
      teammates,
      email: ref(""),
      startDate: ref(""),
      startDateFn(startDate) {
        return startDate >= now;
      },
      endDate: ref(""),
      endDateFn(endDate) {
        return endDate >= now;
      },
      deadline: ref(""),
      deadlineFn(deadline) {
        return deadline >= now;
      },
      customDate: ref(""),
      customDateFn(customDate) {
        return customDate >= now;
      },

      now: now,
      time: ref({
        label: "Days",
        value: "days",
      }),

      timeOptions: [
        {
          label: "Days",
          value: "days",
        },
        {
          label: "Business Days",
          value: "business-days",
        },
        {
          label: "Weeks",
          value: "weeks",
        },
        {
          label: "Months",
          value: "months",
        },
      ],
      sendEmailToggle: ref(true),
      sendCalendarAlertToggle: ref(true),
      sendOnHolidayToggle: ref(true),
      selectedTeam: ref(null),
      teams,
      tab: ref("repeat"),
      panel: ref({
        label: "Since from",
        value: "since-from",
      }),

      sinceFrom: ref({
        label: "Times",
        value: "time",
      }),
      sinceFromOptions: [
        {
          label: "Time",
          value: "Time",
        },
        {
          label: "Until dismissed",
          value: "until-dismissed",
        },
      ],
      reminderTab: ref({
        label: "Repeat every",
        value: "repeat",
      }),
      reminderTabOptions: [
        {
          label: "Repeat every",
          value: "repeat-every",
        },
        {
          label: "One Time",
          value: "one-time",
        },
      ],
      repetition: ref(null),
    };
  },
};
</script>
<style scoped>
.active-tab {
  border: 1px solid #3a4f72 !important;
  color: white !important;
  background-color: #3a4f72 !important;
}
.normal-tab {
  border: 1px solid #3a4f72 !important;
  color: #3a4f72 !important;
  background-color: white !important;
}
@media screen and (max-width: 600px) {
  .w-100{
    width: 100% !important;
  }
}
</style>
