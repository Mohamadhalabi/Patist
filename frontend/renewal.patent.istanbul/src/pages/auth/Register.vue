<template>
  <q-page style="background-color: #f6f7fa">
    <div class="login-container">
      <q-card class="register-card">
        <div class="row">
          <div class="col-lg-5 login-image">
            <img
              src="../../assets/login.webp"
              alt="Image"
              width="250"
              class="login-image"
            />
          </div>
          <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <q-card-section class="login-card-section">
              <h1
                class="text-h6 text-center"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                User Register
              </h1>
              <q-form @submit.prevent="submitForm" class="text-center">
                <q-input
                  v-model="form.name"
                  label="*Full Name"
                  rounded
                  outlined
                  hint="Example: John Doe"
                  :rules="[(val) => !!val || 'Name is required']"
                >
                  <template v-slot:prepend>
                    <q-icon name="person" class="q-pl-sm" />
                  </template>
                </q-input>

                <q-input
                  v-model="form.email"
                  label="*Email Address"
                  rounded
                  outlined
                  type="email"
                  class="q-mt-md"
                  hint="Example: John.doe@gmail.com"
                  :rules="[(val) => !!val || 'Email is required']"
                >
                  <template v-slot:prepend>
                    <q-icon name="mail" class="q-pl-sm" />
                  </template>
                </q-input>
                <q-input
                  v-model="form.password"
                  rounded
                  outlined
                  label="*Password"
                  :rules="[(val) => !!val || 'Password is required']"
                  class="q-mt-md"
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
                <q-input
                  v-model="form.phoneNumber"
                  rounded
                  outlined
                  label="Phone Number"
                  class="q-mt-md"
                  hint="+90 xxx-xxx-xx-xx"
                >
                  <template v-slot:prepend>
                    <q-icon name="phone" class="q-pl-sm" />
                  </template>
                </q-input>
                <q-checkbox
                  v-model="customModel"
                  color="primary"
                  label="I accept receiving emails"
                  true-value="yes"
                  false-value="no"
                  class="q-mt-md"
                />
                <br>
                <q-btn
                  type="submit"
                  class="register-button"
                  rounded
                  color="primary"
                  label="Register"
                />
              </q-form>
            </q-card-section>
          </div>
        </div>
      </q-card>
    </div>
    <br><br><br>
  </q-page>
</template>

<script>
import {ref} from "vue";
import { register, resend } from "src/api/auth";
import {useQuasar} from 'quasar'
import {useRouter} from "vue-router";


export default {
  name: "RegisterPage",
  setup() {
    const q = useQuasar()
    const router = useRouter();
    const name = ref("");
    const email = ref("");
    const phoneNumber = ref("");
    const password = ref("");
    const isPwd = ref(true);
    const isSubmitted = ref(false)
    const buttonDisabled = ref(true)
    const counter = ref(1)
    const customModel = ref('no')
    const buttonText = ref(`Resend Verification email in (${counter.value}s)`)



    const form = ref({
      name: "",
      email: "",
      password: "",
      phoneNumber: "",
    });
    const submitForm = () => {
      if (customModel.value !== 'yes') {
        q.notify({
          message: 'Please accept receiving emails',
          color: 'negative',
          position: 'top-right',
          icon: 'error'
        })
      } else {
       q.notify({
          message: 'Registration successful',
          color: 'positive',
          position: 'top-right',
          icon: 'check'
        })
        register(form.value.name, form.value.email, form.value.password, form.value.phoneNumber)
          .then((response) => {
            if (response.success === true) {
              router.push('/login?email=' + form.value.email)
            }
          })
          .catch((error) => {
            console.log(error)
          });
      }
    }

    const resendVerificationEmail = () => {
      resend(form.value.email)
        .then((response) => {
          document.getElementById("email_verification").innerHTML = response.message;
        })
        .catch((error) => {
          document.getElementById("status").innerHTML = "validation failed";
        });
    }
    return {
      customModel,
      isSubmitted,
      isPwd,
      name,
      email,
      phoneNumber,
      password,
      form,
      buttonDisabled,
      buttonText,
      submitForm,
      resendVerificationEmail
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
  margin-top: 60px;
}
.login-card-section{
  margin-left: 50px
}

.register-card{
  padding: 50px;
}

.register-button{
  margin-top: 25px;
}

@media screen and (max-width: 1024px)
{
  .login-image{
    margin-left: auto;
    margin-right: auto;
    margin-top: 30px;
  }
  .login-card-section{
    margin:0
  }
  .register-button{
    margin-top: 15px;
  }

  .register-card{
    margin-top: 50px;
    padding: 20px;
  }
}
</style>
