<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="row">
          <div class="col-12">
            <h4 class="q-my-xs q-mt-xl q-ml-md text-weight-bold text-primary">
              Failed Requests
            </h4>
          </div>
        </div>
        <div class="q-pa-md row items-start">
          <q-card flat bordered class="monitor-card col-md-4 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Failed Requests</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <p class="text-h6">There are active {{ requests.length }} request.</p>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md">
          <p>In the event of an unexpected problem experienced by our system or the provider during a new renewal request, the requested number and the requesting users are registered. You can see this list below.</p>
          <FailedRequestListTable :requests="requests" />
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import FailedRequestListTable from "src/components/admin/failedRequest/FailedRequestListTable.vue";
import { getFailedRequests } from "src/api/failed-request";

export default {
  components: { FailedRequestListTable },
  name: "IndexPage",
  setup() {
    const requests = ref([]);
    onMounted(() => {
      getFailedRequests()
        .then((res) => {
          requests.value = res.requests;
        })
        .catch((err) => {
          console.log(err);
        });
    });

    return {
      requests,
    };
  },
};
</script>
