export default [
  {
    path: "/it",
    name: "Home",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name: "Home",
      component: () => import("pages/IndexPage.vue") }],
  },
  {
    path: '/it/informazioni',
    name: 'Informazioni-it',
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name: "Informazioni-it",
      component: () => import("src/pages/About.vue") }],
  },
  {
    path: '/it/api',
    name: 'api-it',
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"api-it",
      component: () => import("src/pages/API.vue") }],
  },
  {
    path: '/it/servizi',
    name: 'Servizi',
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Servizi",
      component: () => import("pages/Services.vue") }],
  },
  {
    path: '/it/base-di-conoscenze',
    name: 'Base di Conoscenze',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Base di Conoscenze",
      component: () => import("src/pages/blog/Index.vue") }],
  },
  {
    path: '/it/base-di-conoscenze/:category',
    name: 'Categoria',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Categoria",
      component: () => import("src/pages/blog/Category.vue") }],
  },
  {
    path: '/it/base-di-conoscenze/:category/:slug',
    name: 'Mostra',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Mostra",
      component: () => import("src/pages/blog/Show.vue") }],
  },
  {
    path: '/it/domande-frequenti',
    name: 'Domande frequenti',
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Domande-frequenti",
      component: () => import("src/pages/FAQs.vue") }],
  },
  {
    path: "/it/validazione-brevetto-europeo-in-turchia",
    name: "Validazione brevetti europei in Turchia",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Validazione brevetti europei in Turchia",
      component: () => import("src/pages/services/EPValidationPage.vue") }],
  },
  {
    path: "/it/ingresso-fase-nazionale-pct-in-turchia",
    name: "Ingresso nella fase nazionale del PCT in Turchia",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Ingresso nella fase nazionale del PCT in Turchia",
      component: () => import("src/pages/services/PCTEntryPage.vue") }],
  },
  {
    path: "/it/rinnovi-brevetto-in-turchia",
    name: "Ristoro dei brevetti in Turchia",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Ristoro dei brevetti in Turchia",
      component: () => import("src/pages/services/PatentAnnuityPage.vue") }],
  },
  {
    path: "/it/contatti",
    name: "Contatti",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Contatti",
      component: () => import("pages/ContactPage.vue") }],
  },
  {
    path: "/it/ordini/:slug",
    name: "Ordini",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "",
      name:"Ordini",
      component: () => import("pages/Orders.vue") }],
  },

]
