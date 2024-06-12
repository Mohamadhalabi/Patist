<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Profile Settings
            </h4>
            <p class="q-my-xs q-mt-md q-ml-md">
              You can use the forms below to update your profile information.
            </p>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Personal Information
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="row">
                <div class="col-md-6 self-end">
                  <q-input
                    class="q-ma-md"
                    v-model="personal.name"
                    label="Name"
                    placeholder="Example: John Doe"
                    hint="Enter your name."
                    dense="dense"
                  />
                </div>
                <div class="col-md-6">
                  <q-select
                    class="q-ma-md"
                    v-model="personal.timezone"
                    :options="timezoneOptions"
                    label="Timezone"
                    placeholder="Example: UTC"
                    hint="Select your timezone."
                    dense="dense"
                  />
                </div>
              </div>
            </q-card-section>
            <q-btn
              v-if="profileSaveButton"
              color="primary"
              class="full-width"
              @click="update"
            >
              <q-icon name="save" />
              <div>Save Changes</div>
            </q-btn>
          </q-card>

          <q-card
            flat
            bordered
            class="monitor-card col-md-12 col-sm-12 q-my-md w-100"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Calendar Settings
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <div class="row">
                <div class="col-md-4 col-sm-12 self-center">
                  <q-toggle
                    v-model="personal.use_calendar_password"
                    label="Use calendar password"
                    left-label
                  />
                  <q-input
                    v-model="personal.password"
                    :rules="[(val) => !!val || $t('auth.password-is-required')]"
                    class="q-mt-sm"
                    :type="isPwd ? 'password' : 'text'"
                    hint="Calendar password"
                    autocomplete="off"
                    v-if="personal.use_calendar_password"
                  >
                    <template v-slot:append>
                      <q-icon
                        :name="isPwd ? 'visibility_off' : 'visibility'"
                        class="cursor-pointer"
                        @click="isPwd = !isPwd"
                      />
                    </template>
                    <template v-slot:prepend>
                      <q-icon name="password" class="q-pl-sm" />
                    </template>
                  </q-input>
                </div>
                <div class="col-md-8 self-end col-sm-12 w-100">
                  <div class="q-ma-md row">
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
                </div>
              </div>
            </q-card-section>
            <q-btn
              v-if="calendarSaveButton"
              color="primary"
              class="full-width"
              @click="update"
            >
              <q-icon name="save" />
              <div>Save Changes</div>
            </q-btn>
          </q-card>
          <q-card
            flat
            bordered
            class="monitor-card col-md-12 col-sm-12 q-my-md"
          >
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Contact Information
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="row">
                <div class="col-md-6">
                  <q-input
                    class="q-ma-md"
                    v-model="contact.email"
                    label="Email"
                    placeholder="Example: johndoe@gmail.com"
                    hint="Enter your Email."
                    dense="dense"
                    disable
                  />
                </div>
                <div class="col-md-6">
                  <q-input
                    class="q-ma-md"
                    v-model="contact.phone"
                    label="Phone"
                    placeholder="Example: +90 (555 111 22 33)"
                    hint="Enter your Phone."
                    dense="dense"
                  />
                </div>
              </div>
            </q-card-section>
            <q-btn
              v-if="contactSaveButton"
              color="primary"
              class="full-width"
              @click="update"
            >
              <q-icon name="save" />
              <div>Save Changes</div>
            </q-btn>
          </q-card>

          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Billing Address
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="row">
                <div class="col-md-4">
                  <q-select
                    dense
                    class="q-ma-md"
                    :options="countryOptions"
                    :option-value="'id'"
                    :option-label="'name'"
                    v-model="address.country"
                    label="Country"
                    hint="Select country"
                    @update:model-value="
                      showStates(address.country),
                        $emit('countryName', address.country)
                    "
                  />
                </div>
                <div class="col-md-4">
                  <q-select
                    dense
                    class="q-ma-md"
                    :options="stateOptions"
                    :option-value="'id'"
                    :option-label="'name'"
                    v-model="address.state"
                    label="State"
                    hint="Select state"
                    @update:model-value="
                      showCities(address.state),
                        $emit('stateName', address.state)
                    "
                  />
                </div>
                <div class="col-md-4">
                  <q-select
                    dense
                    class="q-ma-md"
                    label="City"
                    hint="Select city"
                    v-model="address.city"
                    :options="cityOptions"
                    :option-value="'id'"
                    :option-label="'name'"
                    @update:model-value="$emit('cityName', address.city)"
                  />
                </div>
                <div class="col-md-12 col-sm-12">
                  <q-input
                    dense
                    class="q-ma-md"
                    v-model="address.address"
                    label="Address"
                    placeholder="Example: Full Address"
                    hint="Enter your Address."
                  />
                </div>
              </div>
            </q-card-section>
            <q-btn
              v-if="addressSaveButton"
              color="primary"
              class="full-width"
              @click="update"
            >
              <q-icon name="save" />
              <div>Save Changes</div>
            </q-btn>
          </q-card>
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { copyToClipboard } from "quasar";
import { useRouter } from "vue-router";
import { api } from "boot/axios";
import { useAuthStore } from "stores/useAuthStore";
import { updateProfile, getTimeZones } from "src/api/profile";
import { getCalendar } from "src/api/admin/reminders";
export default {
  name: "IndexPage",
  methods: {
    copy(text) {
      copyToClipboard(text)
        .then(() => {
          this.$q.notify({
            color: "primary",
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
    update() {
      const data = {
        name: this.personal.name,
        email: this.contact.email,
        phone: this.contact.phone,
        address: this.address.address,
        state: this.address.state,
        country: this.address.country,
        city: this.address.city,
        timezone: this.personal.timezone,
        use_calendar_password: this.personal.use_calendar_password,
        calendar_password: this.personal.password,
      };
      updateProfile(data)
        .then((res) => {
          if (res.status == "success") {
            this.$q.notify({
              color: "positive",
              message: "Profile updated successfully.",
              icon: "check",
            });
            // Update useAuthStore().user
            useAuthStore().user.name = res.user.name;
            useAuthStore().user.email = res.user.email;
            useAuthStore().user.phone = res.user.phone;
            useAuthStore().user.address = res.user.address;
            useAuthStore().user.state = res.user.state;
            useAuthStore().user.country = res.user.country;
            useAuthStore().user.city = res.user.city;
            useAuthStore().user.timezone = res.user.timezone;
            useAuthStore().user.use_calendar_password =
              res.user.use_calendar_password;
            this.refreshPage();
          }
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            message: "Profile update failed.",
            icon: "warning",
          });
        });
    },
    refreshPage() {
      this.$router.go();
    },
    showStates(country) {
      const states = api.get(`/api/v1/states/${country.id}`);
      states.then((res) => {
        this.stateOptions = res.data;
      });
    },
    showCities(state) {
      const cities = api.get(`/api/v1/cities/${state.id}`);
      cities.then((res) => {
        this.cityOptions = res.data;
      });
    },
  },
  watch: {
    "personal.name": function (val) {
      if (useAuthStore().user.name != val) {
        this.profileSaveButton = true;
      } else {
        this.profileSaveButton = false;
      }
    },
    "personal.timezone": function (val) {
      if (useAuthStore().user.timezone != val) {
        this.profileSaveButton = true;
      } else {
        this.profileSaveButton = false;
      }
    },
    "contact.email": function (val) {
      if (useAuthStore().user.email != val) {
        this.contactSaveButton = true;
      } else {
        this.contactSaveButton = false;
      }
    },
    "contact.phone": function (val) {
      if (useAuthStore().user.phone != val) {
        this.contactSaveButton = true;
      } else {
        this.contactSaveButton = false;
      }
    },
    "address.address": function (val) {
      if (useAuthStore().user.address != val) {
        this.addressSaveButton = true;
      } else {
        this.addressSaveButton = false;
      }
    },
    "address.state": function (val) {
      if (useAuthStore().user.state != val) {
        this.addressSaveButton = true;
      } else {
        this.addressSaveButton = false;
      }
    },
    "address.country": function (val) {
      if (useAuthStore().user.country != val) {
        this.addressSaveButton = true;
      } else {
        this.addressSaveButton = false;
      }
    },
    "address.city": function (val) {
      if (useAuthStore().user.city != val) {
        this.addressSaveButton = true;
      } else {
        this.addressSaveButton = false;
      }
    },
    "personal.use_calendar_password": function (val) {
      if (useAuthStore().user.use_calendar_password != val) {
        this.calendarSaveButton = true;
      } else {
        this.calendarSaveButton = false;
      }
    },
    password: function (val) {
      if (val != "") {
        this.calendarSaveButton = true;
      } else {
        this.calendarSaveButton = false;
      }
    },
  },
  setup() {
    const router = useRouter();
    const countryOptions = ref([]);
    const stateOptions = ref([]);
    const cityOptions = ref([]);
    const timezoneOptions = ref([]);
    const calendar = ref([]);
    const personal = ref({
      name: "",
      timezone: "",
      use_calendar_password: false,
      password: "",
    });
    const contact = ref({
      email: "",
      phone: "",
    });
    const address = ref({
      address: "",
      state: "",
      country: "",
      city: "",
      zip_code: "",
    });
    onMounted(() => {
      getCalendar().then((res) => {
        calendar.value = res;
      });
      console.log(useAuthStore().user);
      personal.value.name = useAuthStore().user.name;
      personal.value.timezone = useAuthStore().user.timezone;
      personal.value.use_calendar_password = useAuthStore().user
        .use_calendar_password
        ? true
        : false;
      personal.value.password = useAuthStore().user.calendar_password;
      contact.value.email = useAuthStore().user.email;
      contact.value.phone = useAuthStore().user.phone;
      address.value.address = useAuthStore().user.address;
      address.value.country = useAuthStore().user.country;
      address.value.state = useAuthStore().user.state;
      address.value.city = useAuthStore().user.city;

      getTimeZones().then((res) => {
        timezoneOptions.value = res.data.timezones;
      });

      const country = api.get(`/api/v1/countries`);
      country.then((res) => {
        countryOptions.value = res.data;
      });
    });

    return {
      calendar,
      isPwd: ref(true),
      personal,
      contact,
      address,
      countryOptions,
      stateOptions,
      cityOptions,
      timezoneOptions,
      profileSaveButton: ref(false),
      contactSaveButton: ref(false),
      addressSaveButton: ref(false),
      calendarSaveButton: ref(false),
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
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
@media screen and (max-width: 600px) {
  .w-100 {
    width: 100% !important;
  }
}
</style>
