<template>
  <q-page style="background-color: #f6f7fa">
    <div class="login-container">
      <q-card class="reset-password">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-center">
            <img
              src="../../assets/login.webp"
              alt="Image"
              width="250"
              class="login-image"
            />
          </div>
          <div
            class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-section text-center form-section"
          >
            <q-card-section class="reset-password-form">
              <h1
                class="text-h6 text-center"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                Create Password
              </h1>
              <q-form @submit.prevent="ResetPassword" class="text-center">
                <q-input
                  v-model="password"
                  label="Password"
                  class="q-mt-xl"
                  rounded
                  outlined
                  :type="isPwd ? 'password' : 'text'"
                  hint="Enter your password"
                  :rules="[(val) => !!val || 'Password is required']"
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
                <q-btn
                  type="submit"
                  rounded
                  color="primary"
                  label="Reset Password"
                  style="text-transform: capitalize"
                />
                <div>
                  <q-btn
                    to="/login"
                    flat
                    class="forgot-password-button q-mt-md"
                  >
                    Back to Sign In
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
import { verifyFirstRegister } from "src/api/auth";
import { useQuasar } from 'quasar'
export default {
  name: "CreatePasswordPage",
  setup() {
    const password = ref("");
    const isPwd = ref(true);
    const email = ref("");
    const $q = useQuasar()
    const ResetPassword = () => {
      const urlParams = new URLSearchParams(window.location.search);
      const hash = urlParams.get('hash');
      verifyFirstRegister(hash,password.value).then((response) => {
        if (response.status === "success") {
          $q.notify({
            message: "Password reset successfully",
            color: "positive",
            position: "bottom",
            icon: "check_circle",
          });
          setTimeout(() => {
            window.location.href = "/login?email="+response.email;
          }, 2000);
        }
        else{
          $q.notify({
            message: "Something went wrong",
            color: "negative",
            position: "bottom",
            icon: "report_problem",
          });
        }
      });
    };
    return {
      password,
      isPwd,
      email,
      ResetPassword,
    };
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
  margin-right: 50px;
  margin-top: 20px;
}
.reset-password {
  padding: 80px;
}
.reset-password-form {
  margin-left: 50px;
}
@media screen and (max-width: 1024px) {
  .reset-password {
    padding: 0;
  }
  .login-image {
    margin-right: 0px;
    margin-top: 0px;
  }
  .reset-password-form {
    margin-left: 0;
  }
}
</style>
