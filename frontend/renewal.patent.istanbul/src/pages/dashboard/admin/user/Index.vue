<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              User - {{ user.name }}
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <div class="row" v-if="authenticatedUser.role == 'proust-admin'">
              <div class="col-md-4 col-sm-12">
                <q-card-section class="q-pt-none">
                <div class="q-my-md">
                  <div class="text-h6">Set Role</div>
                  <p>Current Role: {{ userRole }}</p>
                  <p>You can change the user's role by selecting it below.</p>
                   <div class="row">
                    <div class="col self-center">
                      <div class="q-mr-md">
                        <q-select
                      v-model="userRole"
                      color="primary"
                      label="Roles"
                      class="q-mt-md"
                      :options="['proust-admin', 'proust-user','client-admin', 'client-user']"
                      />
                      </div>
                    </div>
                     <div class="col self-center">
                      <q-btn
                      color="primary"
                      label="Set Role"
                      class="q-mt-md"
                      @click="setRole(user.id, userRole)"
                      />
                     </div>
                   </div>
                </div>
              </q-card-section>
              </div>
              <div class="col-auto">
                <q-card-section class="q-pt-none">
                <div class="q-my-md">
                  <div class="text-h6">Delete User</div>
                  <p>Use the button below to delete the user.</p>
                    <q-btn
                      color="primary"
                      label="Delete User"
                      class="q-mt-md"
                      @click="deleteUserDialog = true"
                    />
                </div>
              </q-card-section>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Personal</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p>
                <p><span class="text-weight-bold">Name:</span> {{ user.name }}</p>
                <p><span class="text-weight-bold">Email:</span> {{ user.email }}</p>
                <p><span class="text-weight-bold">Phone:</span> {{ user.phone }}</p>
                <p><span class="text-weight-bold">Account Verified:</span> {{ user.is_verified ? 'Verified' : 'Unverified' }}</p>
                <p><span class="text-weight-bold">Registered At:</span> {{ new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}</p>
              </p>
            </q-card-section>
              </div>
              <div class="col-md-3">
                <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Address</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p>
                <p><span class="text-weight-bold">Country:</span> {{ location.country ?? 'Empty' }}</p>
                <p><span class="text-weight-bold">State:</span> {{ location.state ?? 'Empty' }}</p>
                <p><span class="text-weight-bold">City:</span> {{ location.city ?? 'Empty' }}</p>
                <p><span class="text-weight-bold">Address:</span> {{ user.address ?? 'Empty' }}</p>
                <p><span class="text-weight-bold">Zip Code:</span> {{ user.zip ?? 'Empty' }}</p>

              </p>
            </q-card-section>
              </div>
              <div class="col-md-3">
                <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Activities</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p>
                <p><span class="text-weight-bold">Total Renewals:</span> {{ renewals.length }}</p>
                <p><span class="text-weight-bold">Active Renewals:</span> {{ renewals.length }}</p>
                <p><span class="text-weight-bold">Completed Renewals:</span> 0</p>
                <p><span class="text-weight-bold">Cancelled Renewals:</span> 0</p>

              </p>
            </q-card-section>
              </div>
            </div>
          </q-card>

        </div>
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
  <q-dialog v-model="deleteUserDialog" persistent>
        <q-card>
          <q-card-section class="row items-center">
            <div class="text-h6 text-weight-bold text-primary">
              Delete Reminder
            </div>
          </q-card-section>
          <q-card-section>
            <div class="text-subtitle1">
                    <span class="text-weight-bold">Warning:</span> When the user is to be deleted, if an email address other than his own account or a team is added to the active reminders so that he can follow the reminder, and if there is someone else in that team, the reminder will <strong>NOT</strong> be deleted.
                    </div>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="Cancel" color="primary" v-close-popup />
            <q-btn
              label="Delete"
              color="primary"
              v-close-popup
              @click="deleteUser(user.id)"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import RenewalListTable from "src/components/admin/renewal/RenewalListTable.vue";
import { getUser, deleteUser, setRole } from "src/api/admin/user";
import { useQuasar } from "quasar";
import { useAuthStore } from "src/stores/useAuthStore";
export default {
  components: { RenewalListTable },
  name: "AdminUserDetails",
  methods: {
    refreshPage() {
      this.$router.go();
    },
    deleteUser(id) {
      deleteUser(id).then((res) => {
        this.$router.push("/dashboard/admin");
      });
    },
    setRole(user,role){
      setRole(user,role).then((res) => {
        // notify
        this.$q.notify({
          color: "primary",
          message: "Role set successfully",
          icon: "check",
        });
      });
    }
  },
  setup() {
    const router = useRouter();
    const renewalListLoaded = ref(false);
    const renewals = ref([]);
    const user = ref([]);
    const location = {
      country: "",
      state: "",
      city: "",
    };
    const userRole = ref("");
    onMounted(() => {
      // user id from router
      const id = router.currentRoute.value.params.id;
      getUser(id).then((res) => {
        user.value = res;
        userRole.value = res.roles[0].name;
        location.country = JSON.parse(user.value.country).name ?? "";
        location.state = JSON.parse(user.value.state).name ?? "";
        location.city = JSON.parse(user.value.city).name ?? "";
        renewals.value = res.renewals;
        renewalListLoaded.value = true;
      });
    });

    return {
      authenticatedUser: useAuthStore().user,
      user,
      userRole,
      location,
      renewals,
      renewalListLoaded,
      iyzicoDialog: ref(false),
      deleteUserDialog: ref(false),
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
