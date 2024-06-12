<template>
  <q-page style="background-color: #f6f7fa">
    <div class="login-container">
      <q-card class="login-card">
        <div class="row justify-center">
          <div class="col-lg-5 login-image">
            <img
              src="../../assets/login.webp"
              alt="Image"
              width="250"
              class="login-image"
            />
          </div>
          <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-section">
            <q-card-section class="login-card-section">
              <h1
                class="text-h6 text-center"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                User Login
              </h1>
              <q-form @submit.prevent="submitForm" class="text-center">
                <q-input
                  v-model="form.email"
                  label="Email"
                  rounded
                  outlined
                  :rules="[(val) => !!val || $t('auth.email-is-required')]"
                >
                  <template v-slot:prepend>
                    <q-icon name="mail" class="q-pl-sm" />
                  </template>
                </q-input>
                <q-input
                  v-model="form.password"
                  rounded
                  outlined
                  :rules="[(val) => !!val || $t('auth.password-is-required')]"
                  class="q-mt-sm"
                  :type="isPwd ? 'password' : 'text'"
                  hint="Password"
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
                <div id="status" class="text-red q-mt-md"></div>
                <p v-if="status == 'verified'" class="text-green">
                  {{ $t("auth.account-verified") }}.
                </p>
                <p v-if="status == 'invalid'" class="text-red">
                  {{ $t("auth.invalid-token") }}.
                </p>

                <q-btn
                  type="submit"
                  class="q-mt-sm"
                  rounded
                  color="primary"
                  label="Login"
                />
                <div>
                  <q-btn
                    to="/reset-password"
                    flat
                    class="forgot-password-button q-mt-md"
                  >
                    Forgot your password?
                  </q-btn>
                </div>
              </q-form>
            </q-card-section>
          </div>
        </div>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import { ref } from "vue";
import { login } from "src/api/auth";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "src/stores/useAuthStore";
import { useQuasar } from 'quasar'
export default {
  name: "LoginPage",
  setup() {
    const $q = useQuasar()
    const user = ref(useAuthStore().user);
    const router = useRouter();
    const form = ref({
      email: "",
      password: "",
    });
    const password = ref("");
    const isPwd = ref(true);
    const submitForm = () => {
      login(form.value.email, form.value.password).then((response) => {
        if (response.success === true) {
          // redirect dashboard
          router.push("/dashboard");
        } else {
          $q.notify({
            message: "Your e-mail or password is incorrect. Please check and try again.",
            color: "primary",
            position: "bottom",
            icon: "report_problem",
          });
        }
      });
    };
    // eğer kullanıcı giriş yaptıysa /dashboard'a yönlendir
    if (user.value) {
      // router.push("/dashboard");
    }
    return {
      user,
      password,
      isPwd,
      form,
      submitForm,
    };
  },
  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get("email");
    if (email) {
      this.form.email = email;
    }
  },
};
</script>
<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.login-image {
  margin-top: 15px;
}

.login-card {
  padding: 80px;
}

@media screen and (max-width: 1024px) {
  .login-image {
    margin-left: auto;
    margin-right: auto;
  }
  .login-card-section {
    margin-left: auto;
  }
  .login-card {
    padding: 0;
  }
}

@media screen and (max-width: 766px) {
  .login-card-section {
    margin-left: auto;
    margin-right: auto;
  }
}

@media screen and (min-width: 1440px) {
  .login-card-section {
    margin-left: 50px;
  }
}
</style>
