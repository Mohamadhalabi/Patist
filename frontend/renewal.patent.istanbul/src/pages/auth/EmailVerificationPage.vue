<template>
  <q-page style="background-color: #f6f7fa">
    <div class="login-container">
      <q-card style="padding: 80px">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-center">
            <img
              src="../../assets/verify-your-email.jpg"
              alt="Image"
              width="250"
            />
          </div>
          <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 form-section text-center">
            <q-card-section>
              <h1
                class="text-h6 text-center"
                style="font-weight: bold; color: #333; font-size: 24px"
              >
                {{ $t('auth.verify-your-email') }}
              </h1>
              <p>{{ $t('auth.to-continue-verify-your-email') }}</p>
              <p id="email_verification" class="q-mt-md">
              </p>
              <q-btn @click="resendEmail" class="q-mt-md" color="primary" style="text-transform: none" :disabled="countdownNotFinished">{{ $t('auth.resend-verification') }}</q-btn>
              <span class="q-mt-md" style="display: block;font-size: 14px;color: dimgrey">{{ minutes.toString().padStart(2, '0') }}:{{ seconds.toString().padStart(2, '0') }}</span>
              <p class="q-mt-lg" v-if="responseStatus === 'success'" style="color: green">{{responseMessage}}</p>
              <p class="q-mt-lg" v-else style="color: red">{{responseMessage}}</p>
            </q-card-section>
          </div>
        </div>
      </q-card>
    </div>
  </q-page>
</template>
<script>
import { computed, onMounted, ref } from "vue";
import {resend} from "src/api/auth";
import {useRoute} from "vue-router";

export default {
  name: "EmailVerificationPage",
  methods: {resend},
  setup() {
    const userEmail = useRoute().query.email
    const countdown = ref(180); // 3 minutes in seconds
    const interval = ref(null);
    const responseMessage = ref("");
    const responseStatus = ref("");
    const resendEmail = () => {
      resend(userEmail)
        .then((response) => {
          responseMessage.value = response.message
          responseStatus.value = response.status
        })
        .catch((error) => {
          console.log(error)
        });
    };

    const startCountdown = () => {
      interval.value = setInterval(() => {
        countdown.value -= 1;
        sessionStorage.setItem("countdown", countdown.value);
        if (countdown.value === 0) {
          clearInterval(interval.value);
        }
      }, 1000);
    };

    onMounted(() => {
      const storedCountdown = sessionStorage.getItem("countdown");
      if (storedCountdown) {
        countdown.value = parseInt(storedCountdown);
      }
      startCountdown();
    });

    const minutes = computed(() => {
      const value = Math.floor(countdown.value / 60);
      return value < 0 ? 0 : value;
    });

    const seconds = computed(() => {
      const value = countdown.value % 60;
      return value < 0 ? 0 : value;
    });

    const countdownNotFinished = computed(() => {
      return countdown.value > 0;
    });

    return{
      responseStatus,
      responseMessage,
      userEmail,
      minutes,
      seconds,
      countdownNotFinished,
      resendEmail
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
  margin-top: 120px;
}
</style>
