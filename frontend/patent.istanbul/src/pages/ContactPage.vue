<template>
  <q-page class="main-container">
    <div className="container">
      <div className="row">
        <div className="col-md-10 col-xs-10 q-mx-auto">
          <div className="contact-us q-pa-md" v-if="msg !=false">
          </div>
          <div v-bind:class="rowClass()">
            <div v-if="msg === false">
              <div className="col-xl-12 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <h3 className="h3-text page-header center">{{ $t('contact.page.title') }}
                  <q-btn class="float-right" icon="close" flat round dense v-close-popup />
                </h3>
              </div>

            </div>
            <div className="col-lg-5 col-md-12 col-sm-12 col-xs-12" v-else>
              <h3 className="h3-text page-header">{{ $t('contact.page.title') }}</h3>
              <p className="page-description">{{ $t('get.in.touch') }} <a class="mail" href="mailto:info@patent.istanbul">info@patent.istanbul</a></p>

              <q-list padding>
                <q-item>
                  <q-item-section avatar>
                    <q-avatar color="primary" text-color="white" circle icon="local_phone" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="page-description">
                      <a class="phone-call" href="tel:+90 212 7061467" style="text-decoration: none;color: black">
                        +90 212 7061467
                      </a>
                    </q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section avatar>
                    <q-avatar color="primary" text-color="white" circle icon="location_on" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="page-description" style="line-height: 24px!important;">İstiklal Cd. No:189,K:2 D:3, Beyoğlu, 34433,<br> İstanbul / Türkiye</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>

            </div>
            <div v-bind:class="contactClass()">
              <q-form @submit="onSubmit" @reset="onReset" className="q-gutter-md ">
                <div className="row">
                  <div  v-bind:class="contactClass()">
                    <q-input
                      v-if="msg !=false"
                      class="q-ml-sm q-mt-md"
                      bg-color="grey-4"
                      label-color="dark"
                      filled
                      v-model="name"
                      :label="$t('your-name')"
                      :hint="$t('your-name-and-surname')"
                      @update:model-value="$emit('changeName',name)"
                      lazy-rules
                      :rules="[
                          (val) => (val && val.length > 0) || $t('name.error.message') ,
                          ]"
                    />
                    <q-input
                      v-else
                      class="q-ml-sm q-mt-md"
                      bg-color="grey-4"
                      label-color="dark"
                      filled
                      v-model="username"
                      :label="$t('your-name')"
                      :hint="$t('your-name-and-surname')"
                      @update:model-value="$emit('changeName',username)"
                      lazy-rules
                      :rules="[
                          (val) => (val && val.length > 0) || $t('name.error.message') ,
                          ]"
                    />
                  </div>
                  <div className="col q-mr-sm" v-bind:class="contactClass()">
                    <q-input
                      v-if="msg !=false"
                      class="q-ml-sm q-mt-md"
                      bg-color="grey-4"
                      label-color="dark"
                      filled
                      v-model="phone"
                      :label="$t('your-phone')"
                      :hint="$t('your-phone-number')"
                      @update:model-value="$emit('changePhone',phone)"
                      maxlength="20"
                      lazy-rules
                      :rules="[
                          (val) =>
                          (val && val.length > 4) ||
                           $t('phone.error.message')
                          ]"
                    />
                    <q-input
                      v-else
                      class="q-ml-sm q-mt-lg"
                      bg-color="grey-4"
                      label-color="dark"
                      filled
                      v-model="userphone"
                      :label="$t('your-phone')"
                      :hint="$t('your-phone-number')"
                      @update:model-value="$emit('changePhone',userphone)"
                      maxlength="20"
                      lazy-rules
                      :rules="[
                          (val) =>
                          (val && val.length > 4) ||
                           $t('phone.error.message')
                          ]"
                    />

                  </div>
                </div>
                <div className="row">
                  <div className="col-lg-6 col-sm-12 col-xs-12 " v-bind:class="contactClass()">
                    <q-input
                      v-if="msg !=false"
                      class="q-ml-sm q-mt-md"
                      bg-color="grey-4"
                      label-color="dark"
                      filled
                      v-model="email"
                      :label="$t('your-email')"
                      :hint="$t('your-email-address')"
                      @update:model-value="$emit('changeEmail',email)"
                      lazy-rules
                      :rules="[
                        (val) => !!val || $t('email.error.message'),
                        isValidEmail,
                        ]"
                    />
                    <q-input
                      v-else
                      class="q-ml-sm q-mt-sm"
                      bg-color="grey-4"
                      label-color="dark"
                      filled
                      v-model="useremail"
                      :label="$t('your-email')"
                      :hint="$t('your-email-address')"
                      @update:model-value="$emit('changeEmail',useremail)"
                      lazy-rules
                      :rules="[
                        (val) => !!val || $t('email.error.message'),
                        isValidEmail,
                        ]"
                    />
                  </div>
                  <div className="col-lg-6 col-sm-12 col-xs-12 " v-bind:class="contactClass()">
                    <div v-if="this.patentNumber != null && this.service != null">
                      <q-input
                        class="q-ml-sm q-mt-md"
                        bg-color="grey-4"
                        label-color="dark"
                        filled
                        v-model="this.patentNumber"
                        :hint="$t('turkish-patent-app-number')"
                      />
                      <q-select
                        class="q-ml-sm q-mt-md"
                        bg-color="grey-4"
                        label-color="dark"
                        filled
                        v-model="this.service"
                        :options="options"
                        :hint="$t('Service')"
                        className="q-mt-md"
                        lazy-rules
                        :rules="[
                            (val) =>
                            (val && val.length > 0) || $t('subject.error.message'),
                            ]"
                      />
                    </div>
                    <div v-else>
                      <div v-if="serviceSubject === undefined && orderNumber === undefined">
                        <q-select
                          class="q-ml-sm q-mt-md"
                          bg-color="grey-4"
                          label-color="dark"
                          filled
                          v-model="subject"
                          :label="$t('subject')"
                          :options="options"
                          :hint="$t('subject')"
                          lazy-rules
                          :rules="[
                              (val) =>
                              (val && val.length > 0) || $t('subject.error.message'),
                              ]"
                        />
                      </div>
                      <div v-if=" orderNumber != undefined">
                        <q-select
                          class="q-ml-sm q-mt-md"
                          bg-color="grey-4"
                          label-color="dark"
                          filled
                          v-model="ContactOrder"
                          :label="$t('subject')"
                          :hint="$t('subject')"
                          lazy-rules
                          :rules="[
                              (val) =>
                              (val && val.length > 0) || $t('subject.error.message'),
                              ]"
                        />
                      </div>
                      <div v-if="serviceSubject !== undefined">
                        <q-select
                          class="q-ml-sm q-mt-md"
                          bg-color="grey-4"
                          label-color="dark"
                          filled
                          v-model="selectedOption"
                          :label="$t('subject')"
                          :options="[serviceSubject]"
                          :hint="$t('subject')"
                          lazy-rules
                          :rules="[
                              (val) =>
                              (val && val.length > 0) || $t('subject.error.message'),
                              ]"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col " v-bind:class="contactClass()">
                  <q-input
                    v-if="Message === undefined"
                    class="q-ml-md q-mt-md "
                    bg-color="grey-4"
                    label-color="dark"
                    v-model="message"
                    filled
                    type="textarea"
                    :label="$t('message')"
                    :hint="$t('Message')"
                    lazy-rules
                    :rules="[
                      (val) => (val && val.length > 0) || $t('message.error.message'),
                      ]"
                  />
                  <q-input
                    v-else
                    class="q-ml-sm q-mt-md "
                    bg-color="grey-4"
                    label-color="dark"
                    v-model="Message"
                    filled
                    type="textarea"
                    :label="$t('message')"
                    :hint="$t('Message')"
                    lazy-rules
                    :rules="[
                      (val) => (val && val.length > 0) || $t('message.error.message'),
                      ]"
                  />
                </div>
                <div>
                  <q-btn
                    style="margin-left: 2%"
                    :label="$t('submit')"
                    type="submit"
                    color="primary"
                    class="q-mt-lg"
                  />
                  <q-btn
                    :label="$t('reset')"
                    type="reset"
                    color="primary"
                    flat
                    class="q-ml-sm q-mt-lg"
                  />
                </div>
              </q-form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>
<script>
import { useQuasar } from "quasar";
import {ref, defineAsyncComponent, onMounted} from "vue";
import { api } from "boot/axios";
import { useMeta } from 'quasar'
import {useRoute} from "vue-router";

const metaData = {
  title: 'Contact Us',
  titleTemplate: title => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: 'canonical',
      href: 'https://patent.istanbul/contact',
    },
  ],
}

export default {
  props: {
    msg: {
      type: String,
    },
    service: {
      type: String,
    },
    patentNumber: {
      type: String,
    },
    validationMessage: {
      type: String,
    },
    serviceSubject: {
      type: String,
    },
    contactMsg: {
      type: String,
    },
    userName: {
      type: String,
    },
    userPhone: {
      type: String,
    },
    userEmail: {
      type: String,
    },
    orderNumber: {
      type: String,
    },
  },
  created() {
    this.selectedOption = this.serviceSubject
    this.Message = this.contactMsg
    this.ContactOrder = 'Instruction: [#'+this.orderNumber+']'
  },

  data(){
    return {
      selectedOption: '',
      Message :'',
      orderOption:'',
      ContactOrder:''
    }
  },
  mounted() {
    this.username = this.userName
    this.userphone = this.userPhone
    this.useremail = this.userEmail
  },
  methods: {
    contactClass(){
      return {
        'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12': this.msg === false,
        'col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12': this.msg != false,
      }
    },
    rowClass(){
      return {
        '': this.msg === false,
        'row': this.msg != false,
      }
    },
    GetValue(value) {
      if (value === false) {
        this.fixed = false;
      }
    },
    isValidEmail(val) {
      const emailPattern =
        /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
      return emailPattern.test(val) || "Invalid email";
    },
  },
  setup() {
    useMeta(metaData)
    const $q = useQuasar();
    const name = ref(null);
    const phone = ref(null);
    const email = ref(null);
    const message = ref(null);
    const subject = ref(null);
    const username = ref(null);
    const userphone = ref(null);
    const useremail = ref(null);
    const route = useRoute()
    const lateEPValidation = ref("");

    onMounted(async () => {
      lateEPValidation.value = (route.query[""]);
      if(lateEPValidation.value !== undefined) {
        lateEPValidation.value = lateEPValidation.value.replace(/-/g, ' ');
        subject.value = lateEPValidation.value
      }
    });

    function showDefault() {
      const dialog = $q.dialog({
        message: "Message sending...",
        progress: true,
        persistent: false,
        ok: false,
      });
    }


    return {
      lateEPValidation,
      name,
      phone,
      email,
      message,
      subject,
      username,
      userphone,
      useremail,
      showDefault,
      show_label: ref(true),
      options: ["EP VALIDATION", "PCT ENTRY", "PATENT ANNUITY","OTHER IP SERVICES","FEEDBACK","API INTEGRATION"],
      fixed: ref(false),
      model: ref(null),
      reason: ref(null),
      onSubmit() {
        const dialog = $q.dialog({
          message: "Message sending...",
          progress: true,
          persistent: false,
          ok: false,
        });
        api
          .post("/api/v1/contact", {
            name: name.value,
            phone: phone.value,
            email: email.value,
            message: message.value,
            subject: subject.value,
          })
          .then((res) => {
            if (res.data.success) {
              dialog.update({
                progress: false,
                message:
                  "<div class='text-center'><i class='q-icon notranslate material-icons' style='font-size: 62px;color:green'>done</i><br><br></div>",
                html: true,
              });
            }
          })
          .catch((err) => {
          });
      },

      onReset() {
        name.value = null;
        phone.value = null;
        email.value = null;
        message.value = null;
        subject.value = null;
        username.value = null;
        userphone.value = null;
        useremail.value = null;
      },
    };
  },
};
</script>
<style>
a.phone-call{
  transition: 0.1s;
}
a.phone-call:hover{
  color: #3a4f72!important;
  font-weight: bold;
}
</style>
