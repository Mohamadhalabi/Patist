<template>
  <div class="column centered">
    <div class="row">
      <h5 class="text-h5 text-dark q-my-md">PATIST Login</h5>
    </div>
    <div class="row">
      <q-card square bordered class="q-pa-lg shadow-1">
        <q-card-section>
          <q-form class="q-gutter-md">
            <q-input
              square
              filled
              v-model="username"
              type="username"
              label="username"
            />
            <q-input
              square
              filled
              v-model="password"
              type="password"
              label="password"
            />
          </q-form>
        </q-card-section>
        <q-card-actions class="q-px-md">
          <q-btn
            unelevated
            color="light-green-7"
            size="lg"
            class="full-width"
            label="Login"
            @click="login"
          />
        </q-card-actions>
      </q-card>
    </div>
  </div>
</template>
<script>
import { LocalStorage, SessionStorage } from 'quasar'
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'
export default {
  setup(){
    const $q = useQuasar()
    const username = ref('')
    const password = ref('')
    const router = useRouter()

    function login(){
        const loginStatus = $q.localStorage.getItem(login)
        if(loginStatus){
        }
        else{
            if(username.value == "admin" && password.value == "password"){
                LocalStorage.set("login", true)
                router.push({ path: '/' })
            }
            else{
                alert("wrong credentials")
            }
        }
    }
    return {
      username,
      password,
      login
    }
  }
};
</script>

<style scoped>
.q-card {
  width: 360px;
}
.centered{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
}
</style>
