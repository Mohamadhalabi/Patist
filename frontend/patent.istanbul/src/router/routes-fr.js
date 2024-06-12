export default [
  {
    path: "/fr",
    name: "Accueil",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Accueil",
        component: () => import("pages/IndexPage.vue")
      }
    ]
  },
  {
    path: '/fr/a-propos',
    name: 'À propos',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "À propos",
        component: () => import("src/pages/About.vue")
      }
    ]
  },
  {
    path: '/fr/api',
    name: 'api-fr',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "api-fr",
        component: () => import("src/pages/API.vue")
      }
    ]
  },
  {
    path: '/fr/services',
    name: 'Services',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Services",
        component: () => import("pages/Services.vue")
      }
    ]
  },
  {
    path: '/fr/base-de-connaissances',
    name: 'Base de connaissances',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Base de connaissances",
        component: () => import("src/pages/blog/Index.vue")
      }
    ]
  },
  {
    path: '/fr/base-de-connaissances/:category',
    name: 'Catégorie',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Catégorie",
        component: () => import("src/pages/blog/Category.vue")
      }
    ]
  },
  {
    path: '/fr/base-de-connaissances/:category/:slug',
    name: 'Afficher',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Afficher",
        component: () => import("src/pages/blog/Show.vue")
      }
    ]
  },
  {
    path: '/fr/foire-aux-questions',
    name: 'Foire aux questions',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Foire aux questions",
        component: () => import("src/pages/FAQs.vue")
      }
    ]
  },
  {
    path: "/fr/validation-de-brevet-europeen-en-turquie",
    name: "Validation de brevet européen en Turquie",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Validation de brevet européen en Turquie",
        component: () => import("src/pages/services/EPValidationPage.vue")
      }
    ]
  },
  {
    path: "/fr/entree-en-phase-nationale-de-pct-en-turquie",
    name: "Entrée en phase nationale PCT en Turquie",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Entrée en phase nationale PCT en Turquie",
        component: () => import("src/pages/services/PCTEntryPage.vue")
      }
    ]
  },
  {
    path: "/fr/renouvellements-de-brevets-en-turquie",
    name: "Renouvellement de brevet en Turquie",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Renouvellement de brevet en Turquie",
        component: () => import("src/pages/services/PatentAnnuityPage.vue")
      }
    ]
  },
  {
    path: "/fr/contact",
    name: "Contact",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Contact",
        component: () => import("pages/ContactPage.vue")
      }
    ]
  },
  {
    path: "/fr/commande/:slug",
    name: "Commande",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Commande",
        component: () => import("pages/Orders.vue")
      }
    ]
  },
]
