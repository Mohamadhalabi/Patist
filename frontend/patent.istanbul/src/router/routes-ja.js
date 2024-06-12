export default [
  {
    path: "/ja",
    name: "ホーム",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "ホーム",
        component: () => import("pages/IndexPage.vue")
      }
    ]
  },
  {
    path: '/ja/私たちについて',
    name: '私たちについて',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "私たちについて",
        component: () => import("src/pages/About.vue")
      }
    ]
  },
  {
    path: '/ja/api',
    name: 'api-ja',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "api-ja",
        component: () => import("src/pages/API.vue")
      }
    ]
  },
  {
    path: '/ja/サービス',
    name: 'サービス',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "サービス",
        component: () => import("pages/Services.vue")
      }
    ]
  },
  {
    path: '/ja/知識データベース',
    name: '知識データベース',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "知識データベース",
        component: () => import("src/pages/blog/Index.vue")
      }
    ]
  },
  {
    path: '/ja/知識データベース/:category',
    name: 'カテゴリー',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "カテゴリー",
        component: () => import("src/pages/blog/Category.vue")
      }
    ]
  },
  {
    path: '/ja/知識データベース/:category/:slug',
    name: '表示',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "表示",
        component: () => import("src/pages/blog/Show.vue")
      }
    ]
  },
  {
    path: '/ja/よくある質問',
    name: 'よくある質問',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "よくある質問",
        component: () => import("src/pages/FAQs.vue")
      }
    ]
  },
  {
    path: "/ja/トルコでの欧州特許有効化",
    name: "トルコでの欧州特許有効化",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "トルコでの欧州特許有効化",
        component: () => import("src/pages/services/EPValidationPage.vue")
      }
    ]
  },
  {
    path: "/ja/PCT-トルコ国内段階進入",
    name: "PCT トルコ国内段階進入",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "PCT トルコ国内段階進入",
        component: () => import("src/pages/services/PCTEntryPage.vue")
      }
    ]
  },
  {
    path: "/ja/トルコでの特許延長",
    name: "トルコでの特許延長",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "トルコでの特許延長",
        component: () => import("src/pages/services/PatentAnnuityPage.vue")
      }
    ]
  },
  {
    path: "/ja/お問い合わせ",
    name: "お問い合わせ",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "お問い合わせ",
        component: () => import("pages/ContactPage.vue")
      }
    ]
  },
  {
    path: "/ja/ご注文/:slug",
    name: "ご注文",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "ご注文",
        component: () => import("pages/Orders.vue")
      }
    ]
  },
]
