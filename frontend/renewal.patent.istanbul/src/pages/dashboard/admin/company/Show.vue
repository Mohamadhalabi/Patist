<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Team Details - {{ team.name }}
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-4 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Team Members
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">
                There are active {{ team.userLength }} teams.
              </p>
            </q-card-section>
          </q-card>
          <q-card flat bordered class="monitor-card q-ml-sm col-md-4 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Delete Team
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">
                Click
                <q-btn
                  outline
                  color="red"
                  label="here"
                  @click="deletePrompt = !deletePrompt"
                />
                to delete the team.
              </p>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row">
          <div class="col-md-6 col-sm-12">
            <q-card flat bordered class="monitor-card q-ml-sm">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">
                  Add Team Member
                </div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <p class="text-body2">Select the user you want to add.</p>
                <q-select
                  bottom-slots
                  v-model="user"
                  :options="userList"
                  :option-value="'id'"
                  :option-label="'email'"
                  label="Team member email"
                >
                  <template v-slot:hint> Select team member </template>

                  <template v-slot:after>
                    <q-btn
                      round
                      dense
                      flat
                      icon="add"
                      @click="addMemberToTotem"
                    />
                  </template>
                </q-select>
              </q-card-section>
            </q-card>
          </div>
        </div>
        <div class="q-pa-md">
          <UserListTable :team="team" />
        </div>
      </div>
      <q-dialog v-model="deletePrompt" persistent>
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">You are about to delete the team</div>
          </q-card-section>

          <q-card-section class="q-pt-none">
            <p class="text-body2">
              To delete the team, type the name of the team
              <q-btn
                outline
                negative
                :label="team.name"
                @click="copy(team.name)"
                size="sm"
                class="q-ma-sm"
              />
              in the field below.
            </p>
            <q-input
              dense
              v-model="deletePromptModel"
              autofocus
              @keyup.enter="deletePrompt = false"
            />
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat label="Cancel" v-close-popup />
            <q-btn
              flat
              label="Delete"
              v-close-popup
              :disabled="deletePromptModel != team.name"
              @click="deleteTeam"
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
import UserListTable from "src/components/admin/team/UserListTable.vue";
import { getTeam, addMemberToTeam, deleteTeam } from "src/api/admin/team";
import { users } from "src/api/admin/user";
import { copyToClipboard } from "quasar";
export default {
  components: { UserListTable },
  name: "TeamDetailsPage",
  methods: {
    copy(text) {
      copyToClipboard(text)
        .then(() => {})
        .catch(() => {
          // fail
        });
    },
    addMemberToTotem() {
      addMemberToTeam(this.team.id, this.user.id)
        .then((res) => {
          if (res.status == "success") {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "User added to team successfully.",
            });
            this.team.value = res.team;
          } else {
            this.$q.notify({
              color: "red-4",
              textColor: "white",
              icon: "cloud_done",
              message: res.message,
            });
          }
        })
        .catch((err) => {
          this.$q.notify({
            color: "red-4",
            textColor: "white",
            icon: "cloud_done",
            message: err,
          });
        });
      this.$router.go();
    },
    deleteTeam() {
      deleteTeam(this.team.id)
        .then((res) => {
          console.log(res);
          if (res.status == "success") {
            this.$q.notify({
              color: "green-4",
              textColor: "white",
              icon: "cloud_done",
              message: "Team deleted successfully.",
            });
            this.team.value = res.team;
            this.$router.push({ name: "TeamIndex" });
          } else {
            this.$q.notify({
              color: "red-4",
              textColor: "white",
              icon: "cloud_done",
              message: res.message,
            });
          }
        })
        .catch((err) => {
          this.$q.notify({
            color: "red-4",
            textColor: "white",
            icon: "cloud_done",
            message: err,
          });
        });
    },
  },
  setup() {
    const router = useRouter();
    const team = ref([]);
    const userList = ref([]);
    onMounted(() => {
      const id = router.currentRoute.value.params.id;
      getTeam(id).then((res) => {
        team.value = res.data;
      });
      users().then((res) => {
        userList.value = res;
      });
    });

    return {
      team,
      userList,
      user: ref(null),
      deletePrompt: ref(false),
      deletePromptModel: ref(""),
    };
  },
};
</script>
