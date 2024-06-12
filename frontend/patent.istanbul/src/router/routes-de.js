export default [
  {
    path: "/de",
    name: "Startseite",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Startseite",
        component: () => import("pages/IndexPage.vue")
      }
    ]
  },
  {
    path: '/de/uber-uns',
    name: 'Über uns',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Über uns",
        component: () => import("src/pages/About.vue")
      }
    ]
  },
  {
    path: '/de/api',
    name: 'api-de',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "api-de",
        component: () => import("src/pages/API.vue")
      }
    ]
  },
  {
    path: '/de/dienstleistungen',
    name: 'Dienstleistungen',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Dienstleistungen",
        component: () => import("pages/Services.vue")
      }
    ]
  },
  {
    path: '/de/wissensdatenbank',
    name: 'Wissensdatenbank',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Wissensdatenbank",
        component: () => import("src/pages/blog/Index.vue")
      }
    ]
  },
  {
    path: '/de/wissensdatenbank/:category',
    name: 'Kategorie',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Kategorie",
        component: () => import("src/pages/blog/Category.vue")
      }
    ]
  },
  {
    path: '/de/wissensdatenbank/:category/:slug',
    name: 'Anzeigen',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Anzeigen",
        component: () => import("src/pages/blog/Show.vue")
      }
    ]
  },
  {
    path: '/de/haufig-gestellte-fragen',
    name: 'Häufig gestellte Fragen',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Häufig gestellte Fragen",
        component: () => import("src/pages/FAQs.vue")
      }
    ]
  },
  {
    path: "/de/europaische-patentvalidierung-in-der-turkei",
    name: "Europäische Patentvalidierung in der Türkei",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Europäische Patentvalidierung in der Türkei",
        component: () => import("src/pages/services/EPValidationPage.vue")
      }
    ]
  },
  {
    path: "/de/pct-nationale-phaseneintritt-in-der-turkei",
    name: "PCT Eintritt in der Türkei",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "PCT Eintritt in der Türkei",
        component: () => import("src/pages/services/PCTEntryPage.vue")
      }
    ]
  },
  {
    path: "/de/patentverlangerung-in-der-turkei",
    name: "Patentverlängerung in der Türkei",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Patentverlängerung in der Türkei",
        component: () => import("src/pages/services/PatentAnnuityPage.vue")
      }
    ]
  },
  {
    path: "/de/kontakt",
    name: "Kontakt",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Kontakt",
        component: () => import("pages/ContactPage.vue")
      }
    ]
  },
  {
    path: "/de/bestellung/:slug",
    name: "Bestellung",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Bestellung",
        component: () => import("pages/Orders.vue")
      }
    ]
  },
]
