<template>
  <q-page class="main-container">
    <div class="row justify-center">
      <div class="col-lg-9 col-md-9 col-sm-10 col-xs-10">
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
          <q-breadcrumbs-el :label="blog.title" icon="article" />
        </q-breadcrumbs>
      </div>
      <div class="col-lg-6 col-md-7 col-sm-11 col-xs-11">
        <div class="col-3">
          <h1 class="text-subtitle2 page-title" style="margin-top: 5%">
            {{ blog.title }}
          </h1>
        </div>
        <div class="col-3">
          <p style="" class="text-left blog-text" v-html="blog.answer"></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-10 col-xs-10 q-pl-xl-lg">
        <p class="page-title text-center" style="margin-top: 10%">All Topics</p>
        <q-list v-for="(blogs, index) in getData" :key="blogs.id">
          <q-item
            clickable
            :active="blogs.question === blog.question"
            :to="`/knowledge-base/${blogs.slug}`"
          >
            <q-item-section avatar style="display: contents">
              <q-avatar text-color="primary">{{
                first_page * total_per_page - total_per_page + index + 1
              }}</q-avatar>
            </q-item-section>
            <q-item-section>
              <q-item-label class="related-topic-content">{{
                blogs.question
              }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-separator spaced inset />
        </q-list>
        <div class="q-pa-lg flex flex-center">
          <q-pagination
            v-model="first_page"
            :min="1"
            :max="Math.ceil(relatedTopics.length / total_per_page)"
            direction-links
            @update:model-value="(val) => showPagenumber(val)"
          >
          </q-pagination>
        </div>
      </div>
      <Card />
    </div>
  </q-page>
</template>

<script>
import { api } from "boot/axios";
import { ref } from "vue";
import { useMeta } from "quasar";
import { useRoute } from "vue-router";
import Card from "pages/blog/Card";

const metaData = {
  title: "European Validation In Turkey",
  titleTemplate: (title) => `${title} - Patist IP Turkey`,
};
export default {
  components: { Card },
  setup() {
    const route = useRoute();
    useMeta(metaData);
    const relatedTopics = ref([]);
    const blog = ref([]);
    const first_page = ref(1);
    const total_per_page = ref(5);
    const index = ref(1);
    return{
      blog,
      relatedTopics,
      first_page,
      total_per_page,
      index,
    }
  },
  computed: {
    getData() {
      return this.relatedTopics.slice(
        (this.first_page - 1) * this.total_per_page,
        (this.first_page - 1) * this.total_per_page + this.total_per_page
      );
    },
  },
  mounted: function () {
    var pagenumber = localStorage.getItem("pageNumber");
    this.first_page = parseInt(pagenumber);
    // get slug
    var slug = this.$route.params.slug;
    api.get(`/api/v1/renewals/knowledge-base/${slug}`).then((res) => {
      this.blog = res.data.data.knowledge_base;
      this.relatedTopics = res.data.data.related_topics;
      // update meta data
      metaData.title = this.blog.title;
    }).catch((err) => {
      this.blog.error = true;
    });
  },
  methods: {
    showPagenumber(val) {
      localStorage.setItem("pageNumber", val);
    },
  },
};
</script>

<style scoped>
p {
  line-height: 30px;
}
.q-item--active {
  font-weight: bold;
}
</style>
