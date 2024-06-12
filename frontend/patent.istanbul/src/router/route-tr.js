export default [
  {
    path: "/tr",
    name: "Anasayfa",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Anasayfa",
        component: () => import("pages/IndexPage.vue"),
      },
    ],
  },
  {
    path: '/tr/hakkinda',
    name: 'Hakkında',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Hakkında",
        component: () => import("src/pages/About.vue"),
      },
    ],
  },
  {
    path: '/tr/api',
    name: 'api-tr',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "api-tr",
        component: () => import("src/pages/API.vue"),
      },
    ],
  },
  {
    path: '/tr/hizmetler',
    name: 'Hizmetler',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Hizmetler",
        component: () => import("pages/Services.vue"),
      },
    ],
  },
  {
    path: '/tr/bilgi-bankasi',
    name: 'Bilgi Bankası',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Bilgi Bankası",
        component: () => import("src/pages/blog/Index.vue"),
      },
    ],
  },
  {
    path: '/tr/bilgi-bankasi/:category',
    name: 'Kategori',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Kategori",
        component: () => import("src/pages/blog/Category.vue"),
      },
    ],
  },
  {
    path: '/tr/bilgi-bankasi/:category/:slug',
    name: 'Göster',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Göster",
        component: () => import("src/pages/blog/Show.vue"),
      },
    ],
  },
  {
    path: '/tr/sikca-sorulan-sorular',
    name: 'Sıkça Sorulan Sorular',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Sıkça Sorulan Sorular",
        component: () => import("src/pages/FAQs.vue"),
      },
    ],
  },
  {
    path: "/tr/avrupa-patent-validasyonu-turkiye",
    name: "Avrupa Patent Validasyonu Türkiye",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Avrupa Patent Validasyonu Türkiye",
        component: () => import("src/pages/services/EPValidationPage.vue"),
      },
    ],
  },
  {
    path: "/tr/pct-ulusal-faz-girisi-turkiye",
    name: "PCT Ulusal Faz Girişi Türkiye",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "PCT Ulusal Faz Girişi Türkiye",
        component: () => import("src/pages/services/PCTEntryPage.vue"),
      },
    ],
  },
  {
    path: "/tr/turkiye-patent-yenileme",
    name: "Türkiye Patent Yenileme",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Türkiye Patent Yenileme",
        component: () => import("src/pages/services/PatentAnnuityPage.vue"),
      },
    ],
  },
  {
    path: "/tr/iletisim",
    name: "İletişim",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "İletişim",
        component: () => import("pages/ContactPage.vue"),
      },
    ],
  },
  {
    path: "/tr/siparis/:slug",
    name: "Sipariş",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Sipariş",
        component: () => import("pages/Orders.vue"),
      },
    ],
  },
]
