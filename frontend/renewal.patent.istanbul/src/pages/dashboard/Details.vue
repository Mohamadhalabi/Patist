<template>
  <q-page class="page-container">
    <div>
      <q-container class="page-container">
        <section
          id="result"
          style="margin-top: 50px; min-height: 800px; display: none"
        >
          <q-card flat bordered class="my-card col-md-4 col-sm-12">
            <q-card-section>
              <h3 id="result-title" class="text-center"></h3>
              <div id="response"></div>
            </q-card-section>
          </q-card>
        </section>
      </q-container>
    </div>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { search } from "src/api/fetch";
import { useQuasar, Loading } from "quasar";
import i18n from "src/i18n";
export default {
  name: "SearchPage",
  methods: {
    submitForm() {
      Loading.show();
      search(this.patentNumber).then((response) => {
        document.getElementById("result").style.display = "block";
        if (response) {
          if (response.status == false) {
            // write error message in #response div
            document.getElementById("result-title").innerHTML = "";
            document.getElementById(
              "response"
            ).innerHTML = `<h4 class="text-center">${i18n.t('no-results-found')}: ${this.patentNumber}</h4>`;
          } else {
            document.getElementById("result-title").innerHTML =
              i18n.t('patent-details') + ' - ' + this.patentNumber;
            // write all response in #response div
            document.getElementById("response").innerHTML = response.data;
          }
        }
        Loading.hide();
      });
    },
  },
  setup() {
    const router = useRouter();
    const $q = useQuasar();

    onMounted(() => {
      const number = router.currentRoute.value.params.id;
      // replace _ to /
      const numberReplaced = number.replace(/_/g, "-");
      search(numberReplaced).then((response) => {
        document.getElementById("result").style.display = "block";
        if (response) {
          if (response.status == false) {
            // write error message in #response div
            document.getElementById("result-title").innerHTML = "";
            document.getElementById(
              "response"
            ).innerHTML = `<h4 class="text-center">No results found: ${numberReplaced}</h4>`;
          } else {
            document.getElementById("result-title").innerHTML =
              "Patent Details - " + numberReplaced;
            // write all response in #response div
            document.getElementById("response").innerHTML = response.data;
          }
        }
        Loading.hide();
      });
    });
    return {
      patentNumber: ref(""),
    };
  },
};
</script>

<style>
.caption {
  padding: 15px 0;
  font-weight: 600;
  font-size: 18px;
}
.caption::after {
  content: "";
  display: block;
  border-top: 1px solid black;
  margin-top: 10px;
}
label > span > span {
  font-weight: 600;
}
table {
  width: 100%;
}
td,
th {
  padding: 0;
  text-align: left;
}
</style>
