<template>
  <q-page style="background-color: #f6f7fa">
    <div class="login-container">
      <q-card class="reset-password">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-center">
            <img
              src="../../assets/reset-password.jpg"
              alt="Image"
              width="250"
              class="login-image"
            />
          </div>
          <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-section">
            <q-card-section class="reset-password-form">
              <h1
                class="text-h6 text-center"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                {{ $t('auth.reset-your-password') }}123
              </h1>
              <q-form @submit.prevent="ResetPassword" class="text-center">
                <q-input
                  v-model="newPassword"
                  :label="$t('auth.password-with-star')"
                  class="q-mt-md"
                  rounded
                  outlined
                  type="password"
                  :rules="[(val) => !!val || $t('auth.password-is-required')]"
                >
                  <template v-slot:prepend>
                    <q-icon name="password" class="q-pl-sm" />
                  </template>
                </q-input>
                <q-input
                  v-model="ConfirmPassword"
                  :label="$t('auth.confirm-password')"
                  class="q-mt-md"
                  rounded
                  outlined
                  type="password"
                  :rules="[(val) => !!val ||  $t('auth.password-is-required')]"
                >
                  <template v-slot:prepend>
                    <q-icon name="password" class="q-pl-sm" />
                  </template>
                </q-input>
                <p v-if="responseStatus =='success'" style="color: #1e7e34">{{responseMessage}}</p>
                <p v-else style="color: red">{{responseMessage}}</p>
                <div id="status" class="text-red q-mt-md"></div>
                <q-btn
                  type="submit"
                  rounded
                  color="primary"
                  :label="$t('auth.reset-password2')"
                  style="text-transform: capitalize"
                />
                <div>
                  <q-btn to="/login"
                         flat
                         class="forgot-password-button q-mt-md"
                  >
                    {{ $t('auth.back-to-sign-in') }}
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
import {ref} from "vue";
import {resetPasswordReset} from "src/api/auth";
import {useRoute} from "vue-router";
export default {
  name: "resetPasswordForm",
  setup() {
    const newPassword = ref("");
    const ConfirmPassword = ref("");
    const isPwd = ref(true);
    const token = ref(useRoute().query.hash)
    const responseMessage = ref("")
    const responseStatus = ref("")

    const ResetPassword = () => {
      resetPasswordReset(newPassword.value,ConfirmPassword.value,token.value).then((response) => {
        console.log(token)
        responseMessage.value = response.message;
        responseStatus.value = response.status;
      })
    };
    return {
      responseStatus,
      responseMessage,
      token,
      newPassword,
      isPwd,
      ConfirmPassword,
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
