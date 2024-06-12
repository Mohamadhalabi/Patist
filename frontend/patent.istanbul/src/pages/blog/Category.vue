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
            :label="category.type"
            icon="density_medium"
            to="/knowledge-base"
          />
          <q-breadcrumbs-el :label="category.title" icon="summarize" />
        </q-breadcrumbs>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-10">
        <h1 class="text-subtitle2 page-title knowledge-base-title">
          {{ category.title }}
        </h1>
      </div>

      <div class="row questions">
        <div v-if="blogData.error" class="col-md-12 text-center">
          <h4>No blog posts have been added yet.</h4>
        </div>
        <div
          v-for="blogs in blogData"
          :key="blogs.id"
          class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
        >
          <q-item
            clickable
            @click="test(blogs.question_link)"
            :to="`${$t('knowledge-base-link')}/${blogs.slug}/${blogs.question_link}`"
          >
            <q-item-section avatar>
              <q-icon color="primary" name="arrow_forward_ios" />
            </q-item-section>
            <q-item-section>
              <p style="margin-top: 20px" class="text-left">
                {{ blogs.question }}
              </p>
            </q-item-section>
          </q-item>
        </div>
      </div>
      <Card />
    </div>
  </q-page>
</template>

<script>
import { ref } from "vue";
import { api } from "boot/axios";
import { useMeta } from "quasar";
import Card from "pages/blog/Card";

const metaData = {
  title: "European Validation In Türkiye",
  titleTemplate: (title) => `${title} - Patist IP Türkiye`,
  link: [
    {
      rel: "canonical",
      href: "https://patent.istanbul/knowledge-base/european-patent-validation-in-turkiye",
    },
  ],
};
export default {
  components: { Card },
  setup() {
    useMeta(metaData);
  },
  data() {
    return {
      blogData: ref([]),
      category: ref([]),
    };
  },
  mounted: function () {
    api
      .get("/api/v1/knowledge-base/" + this.$route.params.category)
      .then((res) => {
        this.blogData = res.data.blogData;
        this.category = res.data.category;
      })
      .catch((err) => {
        this.blogData.error = true;
      });
  },
  methods:{
    test(val){
      var index = this.blogData.map(function(o) { return o.question_link; }).indexOf(val);
      if(index+1 < 6){
        localStorage.setItem("pageNumber", 1);
      }
      if(index+1 >=6 && index+1 <11){
        localStorage.setItem("pageNumber", 2);
      }


    }
  }
};
</script>
