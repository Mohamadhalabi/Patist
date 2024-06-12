<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Company Dashboard
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-4 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Companies</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">
                There are active {{ companies.length ?? 0 }} companies.
              </p>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row">
          <div class="col-md-12 col-sm-12">
            <q-card flat bordered class="monitor-card q-mr-md q-mb-md">
              <q-card-section>
                <div class="text-h5 text-weight-bold text-primary">
                  Create New Company
                </div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                <p class="text-body2">
                  Select the name of the company you are going to create and the
                  manager account.
                </p>
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="q-mr-md">
                      <q-input
                        bottom-slots
                        v-model="form.name"
                        label="Company Name"
                        counter
                      >
                        <template v-slot:hint>
                          Enter the company name.
                        </template>
                      </q-input>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <q-select
                      :options="userList"
                      :option-label="(option) => option.name"
                      :option-value="(option) => option.id"
                      v-model="form.manager"
                      label="Company Manager"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 q-mt-md">
                    <q-btn
                      color="primary"
                      label="Create Company"
                      @click="createCompany"
                    />
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
        <div class="q-pa-md">
          <CompanyListTable :companies="companies" />
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import CompanyListTable from "src/components/admin/company/CompanyListTable.vue";
import { getCompanies, create } from "src/api/admin/company";
import { users } from "src/api/admin/user";

export default {
  components: { CompanyListTable },
  name: "IndexPage",
  methods: {
    createCompany() {
      create(this.form)
        .then((res) => {
          this.$q.notify({
            color: "primary",
            message: "Company created successfully.",
          });

          // update company list
          getCompanies()
            .then((res) => {
              this.companies = res.companies;
            })
            .catch((err) => {
              console.log(err);
            });
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  setup() {
    const router = useRouter();
    const form = ref({
      name: "",
      manager: "",
    });
    const userList = ref([]);
    const companies = ref([]);
    onMounted(() => {
      getCompanies()
        .then((res) => {
          companies.value = res.companies;
          console.log(companies.value)
        })
        .catch((err) => {
          console.log(err);
        });
      users().then((res) => {
        userList.value = res;
      });
    });

    return {
      userList,
      companies,
      form,
    };
  },
};
</script>
