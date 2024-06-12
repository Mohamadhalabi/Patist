<template>
  <q-page class="main-container">
    <div class="row justify-center">
      <div class="col-lg-9 col-md-9 col-sm-10 col-xs-10">
        <q-breadcrumbs class="bread-crumb">
          <template v-slot:separator>
            <q-icon
              size="1.5em"
              name="chevron_right"
              color="primary"
            />
          </template>
          <q-breadcrumbs-el label="Home" icon="home" to="/" />
          <q-breadcrumbs-el label="Knowledge Base" icon="density_medium" to="/knowledge-base" />
          <q-breadcrumbs-el :label="blog.title" icon="summarize" :to="`${$t('knowledge-base-link')}/${blog.slug}`" />
          <q-breadcrumbs-el :label="blog.question" icon="article" />
        </q-breadcrumbs>

      </div>
      <div class="col-lg-6 col-md-7 col-sm-11 col-xs-11">
        <div class="col-3">
          <h1 class="text-subtitle2 page-title" style="margin-top:5%">
            {{ blog.question }}
          </h1>
        </div>
        <div class="col-3">
          <p style="" class="text-left blog-text" v-html="blog.answer">
          </p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-10 col-xs-10 q-pl-xl-lg">
        <p class="page-title text-center" style="margin-top:10%">
          All Topics
        </p>
        <q-list v-for="(blogs,index) in getData" :key="blogs.id">
          <q-item clickable
                  :active="blogs.question === blog.question"
                  :to="`${$t('knowledge-base-link')}/${blogs.slug}/${blogs.question_link}`"
          >
            <q-item-section avatar style="display: contents">
              <q-avatar text-color="primary">{{ first_page * total_per_page - total_per_page + index + 1 }}</q-avatar>
            </q-item-section>
            <q-item-section>
              <q-item-label class="related-topic-content">{{blogs.question}}</q-item-label>
            </q-item-section>
          </q-item>
          <q-separator spaced inset />
        </q-list>
        <div class="q-pa-lg flex flex-center">
          <q-pagination
            v-model="first_page"
            :min="1"
            :max="Math.ceil(relatedTopics.length/ total_per_page)"
            direction-links
            @update:model-value="val => showPagenumber(val)"
          >
          </q-pagination>
        </div>
      </div>
      <Card/>
    </div>
  </q-page>
</template>

<script>
import {api} from "boot/axios";
import {ref} from "vue";
import { useMeta } from 'quasar'
import { useRoute } from 'vue-router';
import Card from "pages/blog/Card";
import i18n from "src/i18n";

;

const metaData = {
  title: 'European Validation In Turkey',
  titleTemplate: title => `${title} - Patist IP Turkey`,
  link: [
    {
      rel: 'canonical',
      href: ''
    }
  ],
}
export default {
  components: {Card},
  setup() {
    const route = useRoute();
    metaData.link[0].href = "https://patent.istanbul" + route.fullPath;
    useMeta(metaData)
    const relatedTopics = ref([])
    const blog = ref([])
    const first_page = ref(1)
    const total_per_page = ref(5)
    const index = ref(1)
    const lang = ref(i18n.locale)
    const getKnowledgeBase = async () => {
      let newString = route.fullPath.replace(/^\/([^/]+\/){3}/, "/knowledge-base/");


      var result="";

      const url = "/es/base-de-conocimientos/validacion-de-patentes-europeas-en-turquia/es-posible-validar-una-patente-europea-en-turquia";
      const parts = route.fullPath.split('/');
      if (parts.length > 3) {
        result = parts.slice(3).join('/');
      }

      console.log(result)

      const languages = ["es", "ja", "fr", "ko", "de", "it", "tr"];
      const firstPart = route.fullPath.split("/")[1];
      const isLanguage = languages.includes(firstPart);
      if (isLanguage) {
        try {
          const {data} = await api.get("/api/v1/knowledge-base/" + result)
          blog.value = data.blog
          relatedTopics.value = data.relatedTopics

        } catch (err) {
          console.log(err)
        }
      } else {
        try {
          const {data} = await api.get("/api/v1" + route.fullPath)
          blog.value = data.blog
          relatedTopics.value = data.relatedTopics
        } catch (err) {
        }
      }
      ;
    }
    getKnowledgeBase()
    return {
      lang: lang.value, // Accessing the locale language value
      relatedTopics,
      blog,
      first_page,
      total_per_page,
      index
    };
  },
  computed:{
    getData(){
      return this.relatedTopics.slice((this.first_page-1)*this.total_per_page,(this.first_page-1)*this.total_per_page+this.total_per_page)
    },
  },
  mounted: function () {
    // this.lang = this.i18n.locale
    var pagenumber = localStorage.getItem("pageNumber");
    this.first_page = parseInt(pagenumber)

  },
  methods:{
    showPagenumber(val){
      localStorage.setItem("pageNumber", val);
    },
  },
};

</script>
<style scoped>
p{
  line-height: 30px;
}
.q-item--active{
  font-weight: bold;
}
</style>
