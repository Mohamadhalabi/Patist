<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Admin Dashboard
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <div class="col-md-3 col-sm-12">
            <q-card flat bordered class="monitor-card q-mb-md q-mr-md">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">User</div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <p class="text-h6">
                  There are {{ userList.length }} registered user.
                </p>
              </q-card-section>
            </q-card>
          </div>

          <div class="col-md-3 col-sm-12">
            <q-card flat bordered class="monitor-card q-mb-md q-mr-md">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">
                  Renewals
                </div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <p class="text-h6">
                  {{ renewals.length }} application numbers tracking
                </p>
              </q-card-section>
            </q-card>
          </div>

          <div class="col-md-3 col-sm-12">
            <q-card flat bordered class="monitor-card">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">Payment</div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <div class="row">
                  <div class="col-12">
                    <p class="text-h6 text-weight-medium">
                      There are {{ pendingPayment.count }} unpaid applications
                    </p>
                    <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
        <div class="q-pa-md">
          <UserListTable v-if="userListLoaded" :data="userList" />
        </div>
        <!-- <div class="q-pa-md">
          <ReminderListTable v-if="reminderListLoaded" :data="reminderList" />
        </div> -->
        <!-- <div class="q-pa-md">
          <CustomReminderListTable v-if="customReminderListLoaded" :data="customReminderList" />
        </div> -->
        <div class="q-pa-md">
          <RenewalListTable v-if="renewalListLoaded" :data="renewals" />
        </div>
      </div>
    </q-page-container>
  </q-page>
  <q-dialog v-model="iyzicoDialog" @hide="refreshPage()">
    <q-card>
      <q-card-section>
        <div class="text-h6">Payment Form</div>
      </q-card-section>
      <q-separator />
      <q-card-section style="min-height: 720px">
        <iframe
          scrolling="no"
          frameBorder="0"
          style="height: 720px; width: 500px"
          :src="paymentPageUrl + '&iframe=true'"
        ></iframe>
      </q-card-section>
      <q-separator />
    </q-card>
  </q-dialog>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "stores/useAuthStore";
import RenewalListTable from "src/components/admin/renewal/RenewalListTable.vue";
import UserListTable from "src/components/admin/user/UserListTable.vue";
import ReminderListTable from "src/components/admin/reminder/ReminderListTable.vue";
// import CustomReminderListTable from 'src/components/admin/reminder/CustomReminderListTable.vue';
import { getRenewals } from "src/api/fetch";
import { users } from "src/api/admin/user";
import { reminders } from "src/api/admin/reminders";
// import { customReminders } from "src/api/admin/custom-reminders";

export default {
  components: {
    RenewalListTable,
    UserListTable,
    ReminderListTable,
    //  CustomReminderListTable
  },
  name: "AdminIndex",
  methods: {
    refreshPage() {
      this.$router.go();
    },
  },
  setup() {
    const router = useRouter();
    const renewalListLoaded = ref(false);
    const userListLoaded = ref(false);
    const reminderListLoaded = ref(false);
    const reminderList = ref([]);
    // const customReminderListLoaded = ref(false);
    // const customReminderList = ref([]);
    const renewals = ref([]);
    const userList = ref([]);
    const pendingPayment = ref({
      amount: 0,
      count: 0,
    });

    onMounted(() => {
      users().then((res) => {
        userList.value = res;
        userListLoaded.value = true;
      });
      reminders().then((res) => {
        reminderList.value = res;
        reminderListLoaded.value = true;
      });
      // customReminders().then((res) => {
      //   customReminderList.value = res;
      //   customReminderListLoaded.value = true;
      // });
      getRenewals()
        .then((res) => {
          renewals.value = res.renewals;
          renewalListLoaded.value = true;
          renewals.value.forEach((renewal) => {
            if (renewal.status == "payment-pending") {
              pendingPayment.value.amount += renewal.total_amount_eur;
              pendingPayment.value.count += 1;
            }
          });
        })
        .catch((err) => {
          console.log(err);
        });
    });

    return {
      userList,
      userListLoaded,

      renewals,
      renewalListLoaded,

      reminderList,
      reminderListLoaded,

      // customReminderList,
      // customReminderListLoaded,

      pendingPayment,
      iyzicoDialog: ref(false),
      paymentPageUrl: ref(""),
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
