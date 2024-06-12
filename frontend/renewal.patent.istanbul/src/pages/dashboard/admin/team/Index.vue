<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Team Dashboard
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-4 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Teams</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">There are active {{ teams.length }} teams.</p>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row">
          <div class="col-md-6 col-sm-12">
            <q-card flat bordered class="monitor-card q-mr-md q-mb-md">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">
                  Create New Team
                </div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <p class="text-body2">
                  Enter the name of the team you want to create. Then you can
                  add/remove members from the team you created from the team
                  details.
                </p>
                <q-input
                  bottom-slots
                  v-model="form.team"
                  label="Team Name"
                  counter
                >
                  <template v-slot:append>
                    <q-icon
                      v-if="form.team !== ''"
                      name="close"
                      @click="form.team = ''"
                      class="cursor-pointer"
                    />
                  </template>

                  <template v-slot:hint> Enter the team name. </template>

                  <template v-slot:after>
                    <q-btn round dense flat icon="add" @click="createNewTeam" />
                  </template>
                </q-input>
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-6 col-sm-12">
            <q-card flat bordered class="monitor-card">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">
                  Show Team Details
                </div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <p class="text-body2">
                  Select the team you want to see the details of and click the
                  search icon.
                </p>
                <q-select
                  bottom-slots
                  v-model="searchTeam"
                  :options="teams"
                  :option-value="'id'"
                  :option-label="'name'"
                  label="Team Name"
                >
                  <template v-slot:hint> Select team </template>

                  <template v-slot:after>
                    <q-btn round dense flat icon="search" @click="showTeam"/>
                  </template>
                </q-select>
              </q-card-section>
            </q-card>
          </div>
        </div>
        <div class="q-pa-md">
          <TeamListTable :teams="teams" />
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import TeamListTable from "src/components/admin/team/TeamListTable.vue";
import { getTeams, createTeam } from "src/api/admin/team";

export default {
  components: { TeamListTable },
  name: "IndexPage",
  methods: {
    createNewTeam() {
      createTeam(this.form)
        .then((res) => {
          this.$q.notify({
            message: "Team created successfully.",
            color: "positive",
            icon: "check",
          });
          this.form.team = "";
          this.teams.push(res.team);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    showTeam() {
      // eğer team seçilmişse
      if (this.searchTeam !== "") {
        this.$router.push({
          name: "TeamShow",
          params: { id: this.searchTeam.id },
        });
      } else {
        this.$q.notify({
          message: "Please select a team.",
          color: "negative",
          icon: "warning",
        });
      }
    },
  },
  setup() {
    const router = useRouter();
    const teams = ref([]);
    const form = ref({
      team: "",
    });

    onMounted(() => {
      getTeams()
        .then((res) => {
          teams.value = res;
        })
        .catch((err) => {
          console.log(err);
        });
    });

    return {
      teams,
      team: ref(""),
      searchTeam: ref(""),
      form,
    };
  },
};
</script>
