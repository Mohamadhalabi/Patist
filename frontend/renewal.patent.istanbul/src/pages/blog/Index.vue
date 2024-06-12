<template>
  <q-page class="main-container">
    <div class="row justify-center">
      <div class="col-lg-10 col-md-9 col-sm-10 col-xs-10">
        <q-breadcrumbs class="bread-crumb">
          <template v-slot:separator>
            <q-icon size="1.5em" name="chevron_right" color="primary" />
          </template>
          <q-breadcrumbs-el label="Home" icon="home" to="/" />
          <q-breadcrumbs-el
            label="Knowledge Base"
            icon="density_medium"
            to="/knowledge-base"
          />
        </q-breadcrumbs>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-10">
        <h1 class="text-subtitle2 page-title knowledge-base-title">
          Knowledge Base
        </h1>
      </div>

      <div class="row questions">
        <div v-if="articles.error" class="col-md-12 text-center">
          <h4>No blog posts have been added yet.</h4>
        </div>
        <div
          v-for="article in articles"
          :key="article.id"
          class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
        >
          <q-item clickable :to="`/knowledge-base/${article.slug}`"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="arrow_forward_ios" />
            </q-item-section>
            <q-item-section>
              <p style="margin-top: 20px" class="text-left">
                {{ article.title }}
              </p>
            </q-item-section>
          </q-item>
        </div>
      </div>
      <!-- <Card /> -->
    </div>
  </q-page>
</template>

<script>
import { ref } from "vue";
import { api } from "boot/axios";
import { useMeta } from "quasar";

const metaData = {
  title: "Patent Renewal In Türkiye",
  titleTemplate: (title) => `Knowledge Base - ${title}`,
};
export default {
  setup() {
    useMeta(metaData);
  },
  data() {
    return {
      articles: ref([]),
    };
  },
  mounted: function () {
    api
      .get("/api/v1/renewals/knowledge-base")
      .then((res) => {
        this.articles = res.data.data;
      })
      .catch((err) => {
        this.articles.error = true;
      });
  },
};
</script>
