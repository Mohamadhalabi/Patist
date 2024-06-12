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
          <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-section text-center form-section" v-if="!isSubmitted">
            <q-card-section class="reset-password-form">
              <h1
                class="text-h6 text-center"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                Forgot Your Password?
              </h1>
              <q-form @submit.prevent="ResetPassword" class="text-center">
                <q-input
                  v-model="email"
                  label="Email Address"
                  class="q-mt-xl"
                  rounded
                  outlined
                  type="email"
                  :rules="[(val) => !!val || 'Email is required']"
                >
                  <template v-slot:prepend>
                    <q-icon name="mail" class="q-pl-sm" />
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
                  <q-btn to="/login"
                         flat
                         class="forgot-password-button q-mt-md"
                  >
                    Back to Sign In
                  </q-btn>
                </div>
              </q-form>
            </q-card-section>
          </div>
          <div class="col-lg-7" v-else>
            <q-card-section>
              <h1
                class="text-h6 text-left"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                {{ $t('auth.check-your-email') }}
              </h1>
              <div class="text-left">
                <p>{{ $t('auth.we-have-sent-to') }}</p>
                <p><b>{{email}}</b></p>

                <p>{{ $t('auth.if-it-doesnt-arrive-soon') }} <a href="/reset-password">{{ $t('auth.send-the-email-again') }}</a></p>
                <q-btn to="/login"
                       flat
                       class="back-to-login q-mt-md"
                >
                  {{ $t('auth.back-to-sign-in') }}
                </q-btn>
              </div>
            </q-card-section>
          </div>
        </div>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import {ref} from "vue";
import { resetPassword } from "src/api/auth";
export default {
  name: "ResetPasswordPage",
  setup() {
    const password = ref("");
    const isPwd = ref(true);
    const email = ref("");
    const isSubmitted = ref(false)

    const ResetPassword = () => {
      resetPassword(email.value).then((response) => {
        console.log(response);
      });
      isSubmitted.value = true;
    };
    return {
      password,
      isPwd,
      isSubmitted,
      email,
      ResetPassword,

    };
  },
}
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
.reset-password{
  padding: 80px;
}
.reset-password-form{
  margin-left: 50px;
}
@media screen and (max-width: 1024px){
  .reset-password{
    padding: 0;
  }
  .login-image {
    margin-right: 0px;
    margin-top: 0px;
  }
  .reset-password-form{
    margin-left: 0;
  }
}
</style>
