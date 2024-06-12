<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              {{ reminder.code }} -
              {{
                reminder.workflow_status == "being-followed"
                  ? "Existing Renewal Instruction"
                  : "New Renewal Instruction"
              }}
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            class="monitor-card col-md-12 col-sm-12 bg-grey-2"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Reminder Actions
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="q-ma-md">
                <div class="row">
                  <div class="col-3">
                    <p><strong>Delete Reminder</strong></p>
                    <p>
                      Are you sure you want to delete the reminder you are
                      currently viewing? This action cannot be undone.
                    </p>
                    <q-btn
                      color="negative"
                      label="Delete"
                      @click="confirm = true"
                    />
                    <q-dialog v-model="confirm" persistent>
                      <q-card>
                        <q-card-section class="row items-center">
                          <q-avatar
                            icon="delete"
                            color="negative"
                            text-color="white"
                          />
                          <span class="q-ml-sm"
                            >You are currently not connected to any
                            network.</span
                          >
                        </q-card-section>

                        <q-card-actions align="right">
                          <q-btn
                            flat
                            label="Cancel"
                            color="primary"
                            v-close-popup
                          />
                          <q-btn
                            label="Delete"
                            color="negative"
                            v-close-popup
                            @click="deleteReminder(reminder.id)"
                          />
                        </q-card-actions>
                      </q-card>
                    </q-dialog>
                  </div>
                  <div class="col-3 q-ml-md">
                    <p><strong>Update Reminder Status</strong></p>
                    <p>
                      You can activate/deactivate Reminder. If you disable it,
                      it will not appear in your calendar and you will not
                      receive notifications.
                    </p>
                    <div class="rounded-borders">
                      <q-option-group
                        name="preferred_genre"
                        v-model="updateModel"
                        :options="updateOptions"
                        color="primary"
                        inline
                      />
                    </div>
                  </div>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            class="monitor-card col-md-12 col-sm-12 bg-grey-2"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Communication History
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="q-ma-md">
                <p v-for="(email, index) in reminder.email_history">
                  {{ index + 1 }} -
                  {{
                    new Date(email.created_at).toLocaleDateString("en-US", {
                      year: "numeric",
                      month: "short",
                      day: "numeric",
                    })
                  }}
                  - <i>{{ email.type.toUpperCase() }}, </i>
                  <strong
                    ><q-btn
                      flat
                      class="q-ma-none q-pa-sm"
                      color="primary"
                      @click="openEmailDetail(email)"
                      >[{{ email.title }}]</q-btn
                    ></strong
                  >
                  {{ email.description }}
                </p>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.1.1'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">RNWo.1.1</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div>
                <p>
                  Accept incoming renewal application to provide checks and
                  renew
                </p>
                <div
                  class="q-my-md"
                  v-if="reminder.workflow_status == 'being-followed'"
                >
                  <p class="q-mb-none">Confirm renewal status</p>
                  <div>
                    <q-toggle
                      :label="`${
                        acceptInstructionToggle
                          ? 'Send notification of acceptance'
                          : 'Don\'t send notification of acceptance'
                      }`"
                      color="primary"
                      v-model="acceptInstructionToggle"
                      :disable="!status['RNWo.1.1']"
                    />
                  </div>
                </div>
                <q-btn
                  color="primary"
                  label="Instruction accepted"
                  @click="updateFlow('RNWo.1.1')"
                  :disable="!status['RNWo.1.1']"
                />
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.1.2'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">RNWo.1.2</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="">
                <div class="">
                  <p>
                    If the fee has not been paid and the number of days left in
                    the login area, the user will be sent an e-mail stating that
                    the fee must be paid.
                  </p>
                </div>
                <div style="display: flex">
                  <div class="">
                    <q-input
                      filled
                      v-model.number="reminderDay"
                      type="number"
                      label="Day"
                      :disable="!status['RNWo.1.2']"
                    />
                  </div>
                </div>
                <div class="q-mt-md">
                  <p class="q-mb-none">Confirm renewal status</p>
                  <div>
                    <q-toggle
                      model="confirmToggle"
                      :label="`${
                        confirmToggle ? 'Step completed' : 'Unable to renew'
                      }`"
                      color="primary"
                      v-model="confirmToggle"
                      :disable="!status['RNWo.1.2']"
                    />
                  </div>
                </div>
                <div class="">
                  <p class="q-mb-none">
                    Reminder mail status to be sent for payment:
                  </p>
                  <q-toggle
                    v-model="sendEmailToggle"
                    :label="`${
                      nextYearToggle
                        ? 'Send reminder email'
                        : 'Do not send reminder email'
                    }`"
                    color="primary"
                    @update:modelValue="updateEmailSetting"
                  />
                </div>
                <q-btn
                  class="q-mt-md"
                  color="primary"
                  label="Complete"
                  @click="updateFlow('RNWo.1.2')"
                  :disable="!status['RNWo.1.2']"
                />
              </div>
              <div></div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.2.1'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">RNWo.2.1</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="">
                <p>
                  Waiting for user or system administrator to confirm/verify
                  payment.
                </p>
              </div>

              <div class="">
                <q-btn
                  color="primary"
                  label="Confirm payment"
                  :disable="!status['RNWo.2.1']"
                  @click="updateFlow('RNWo.2.1')"
                />
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.2.2'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">RNWo.2.2</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="">
                <p><span class="text-muted">Payment completed.</span></p>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.2.3'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">RNWo.2.3</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="">
                <p>
                  <span class="text-muted"
                    >The team is expected to complete the renewal process for
                    the application.</span
                  >
                </p>
                <q-btn
                  color="primary"
                  label="Renewal Process Completed"
                  :disable="!status['RNWo.2.3']"
                  @click="updateFlow('RNWo.2.3')"
                />
              </div>
              <div></div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.3'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">RNWo.3</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="">
                <p>
                  <span class="text-muted">Instruction finished(w.n.p)</span>
                </p>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="">
                    <p class="">
                      Notification, documents and invoice have been sent to the
                      user that the renewal process has been completed.
                    </p>
                    <q-btn
                      color="primary"
                      label="Approve"
                      :disable="!status['RNWo.3']"
                      @click="updateFlow('RNWo.3')"
                    />
                  </div>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.4'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                RNWo.4 - RNWo.5.a - RNWo.5.b
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="">
                <p>
                  Renewal for next year
                  <strong>{{ annuityStatus + 1 }}, possible</strong>.
                </p>
                <p>Choose whether to track renewals for next year</p>
              </div>
              <div>
                <q-toggle
                  model="nextYearToggle"
                  :label="`${
                    nextYearToggle
                      ? 'Add reminder for next renewal'
                      : 'Do not add reminder for next renewal'
                  }`"
                  color="primary"
                  v-model="nextYearToggle"
                  :disable="!status['RNWo.4']"
                />
              </div>
              <div class="q-mt-md">
                <q-btn
                  color="primary"
                  label="Renewal completed"
                  :disable="!status['RNWo.4']"
                  @click="updateFlow('RNWo.4')"
                />
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start">
          <q-card
            flat
            bordered
            :class="`monitor-card col-md-12 col-sm-12 ${
              status['RNWo.5'] ? 'bg-blue-1' : 'bg-blue-grey-2'
            }`"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Workflow Completed
              </div>
            </q-card-section>
          </q-card>
        </div>
      </div>
      <q-dialog v-model="emailDetailModal">
        <q-card>
          <q-card-section>
            <div class="text-h6">{{ emailDetail.title }}</div>
          </q-card-section>

          <q-card-section class="q-pt-none">
            <div class="q-ma-md">
              <p>{{ emailDetail.description }}</p>
              <hr />
              <p>{{ emailDetail.content }}</p>
            </div>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="OK" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref, reactive } from "vue";
import { useRouter } from "vue-router";
import {
  getReminder,
  updateReminder,
  updateEmailSetting,
  deleteReminder,
  updateReminderStatus,
} from "src/api/admin/reminders";

export default {
  components: {},
  name: "IndexPage",
  methods: {
    updateFlow(statusCode) {
      const data = {
        sendEmailToggle: this.nextYearToggle,
        reminderDay: this.reminderDay,
        nextYearToggle: this.nextYearToggle,
        confirmToggle: this.confirmToggle,
      };
      updateReminder(this.reminder.id, { status: statusCode, data: data })
        .then((res) => {
          this.currentStatus = res.status;
        })
        .catch((err) => {
          console.log(err);
        });

      // refresh page
      // this.$router.go();
    },
    updateEmailSetting(status) {
      this.sendEmailToggle = status;
      const data = {
        status: "updateEmail",
        send_email: status ? 1 : 0,
      };
      updateEmailSetting(this.reminder.id, { status: data.status, data }).then(
        (res) => {}
      );
    },
    openEmailDetail(email) {
      console.log(email);
      this.emailDetailModal = true;
      this.emailDetail = email;
    },
    deleteReminder(id) {
      deleteReminder(id).then((res) => {
        this.$router.push("/dashboard/admin/reminders");
      });
    },
  },
  setup() {
    const router = useRouter();
    const renewals = ref([]);
    const reminder = ref([]);
    const isPayment = ref(false);
    const currentStatus = ref(null);
    const annuityStatus = ref(4);
    const sendEmailToggle = ref(null);
    const emailDetailModal = ref(false);
    const emailDetail = ref({});
    const updateModel = ref(null);
    const status = reactive({
      "RNWo.1.1": false,
      "RNWo.1.2": false,
      "RNWo.2.1": false,
      "RNWo.2.2": false,
      "RNWo.2.3": false,
      "RNWo.3": false,
      "RNWo.4": false,
      "RNWo.5": false,
    });
    onMounted(() => {
      const id = router.currentRoute.value.params.id;

      getReminder(id).then((res) => {
          status[res.status] = true;
          reminder.value = res;
          currentStatus.value = res.status;
          updateModel.value = res.is_active ? "active" : "inactive";
          if (res.renewal.is_payment) {
            sendEmailToggle.value = true;
            isPayment.value = true;
          } else {
            sendEmailToggle.value = res.send_email === 0 ? false : true;
          }
        });

      // Fonksiyonu tanımlayın
      const fetchData = () => {
        getReminder(id).then((res) => {
          status[res.status] = true;
          reminder.value = res;
          currentStatus.value = res.status;
          updateModel.value = res.is_active ? "active" : "inactive";
          if (res.renewal.is_payment) {
            sendEmailToggle.value = true;
            isPayment.value = true;
          } else {
            sendEmailToggle.value = res.send_email === 0 ? false : true;
          }
        });
      };

      // setInterval ile her 3 saniyede bir çağırın
      const intervalId = setInterval(fetchData, 3000);
    });

    return {
      emailDetail,
      emailDetailModal,
      status,
      renewals,
      reminder,
      currentStatus,
      step1: ref(true),
      step2: ref(true),
      step3: ref(false),
      file1: ref(null),
      file2: ref(null),
      reminderDay: ref(10),
      acceptInstructionToggle: ref(true),
      nextYearToggle: ref(true),
      sendEmailToggle,
      confirmToggle: ref(true),
      annuityStatus,
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
    };
  },
};
</script>

<style lang="scss">
.page-container {
  background-image: none;
  background-color: #f6f7fa;
}
.q-page-container {
  padding-left: 0;
}
</style>
