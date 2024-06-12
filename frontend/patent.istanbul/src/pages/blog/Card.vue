<template>
  <q-card class="col-lg-9 col-md-8 col-sm-11 col-xs-11 automated-quote">
    <q-card-section>
      <div class="row items-center patist-card">
        <div class="col-lg-3 col-md-3 col-sm-4 patist-logo">
          <q-btn
            flat
            :to="`${$t('home-link')}`"
          >
            <q-img
              src="/images/logo2.png"
              spinner-color="white"
              style="width: 140px"
            />
          </q-btn>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 text-center" style="margin-top:2%">
          <p style="font-size: 16px;font-family: 'EB Garamond',serif;">
            {{ $t("get-an-automated-quote") }}
          </p>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 text-center" style="margin-top:1.25%; font-family: 'Raleway',serif ">
          <q-btn
            color="primary"
            :label="`${$t('go-to-service')}`"
            :to="`${$t('eupatentvalidation')}`"
          />
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Card"
}
</script>

<style scoped>

</style>
