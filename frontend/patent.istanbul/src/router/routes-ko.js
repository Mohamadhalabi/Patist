export default [
  {
    path: "/ko",
    name: "홈",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "홈",
        component: () => import("pages/IndexPage.vue")
      }
    ]
  },
  {
    path: '/ko/우리에-대해',
    name: '우리에 대해',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "우리에 대해",
        component: () => import("src/pages/About.vue")
      }
    ]
  },
  {
    path: '/ko/api',
    name: 'api-ko',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "api-ko",
        component: () => import("src/pages/API.vue")
      }
    ]
  },
  {
    path: '/ko/서비스',
    name: '서비스',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "서비스",
        component: () => import("pages/Services.vue")
      }
    ]
  },
  {
    path: '/ko/지식-데이터베이스',
    name: '지식 데이터베이스',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "지식 데이터베이스",
        component: () => import("src/pages/blog/Index.vue")
      }
    ]
  },
  {
    path: '/ko/지식-데이터베이스/:category',
    name: '카테고리',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "카테고리",
        component: () => import("src/pages/blog/Category.vue")
      }
    ]
  },
  {
    path: '/ko/지식-데이터베이스/:category/:slug',
    name: '보기',
    props: true,
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "보기",
        component: () => import("src/pages/blog/Show.vue")
      }
    ]
  },
  {
    path: '/ko/자주-묻는-질문',
    name: '자주 묻는 질문',
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "자주 묻는 질문",
        component: () => import("src/pages/FAQs.vue")
      }
    ]
  },
  {
    path: "/ko/터키에서-유럽-특허-유효성-검증",
    name: "터키에서 유럽 특허 유효성 검증",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "터키에서 유럽 특허 유효성 검증",
        component: () => import("src/pages/services/EPValidationPage.vue")
      }
    ]
  },
  {
    path: "/ko/PCT-국가-진입-터키",
    name: "PCT 터키 진입",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "PCT 터키 진입",
        component: () => import("src/pages/services/PCTEntryPage.vue")
      }
    ]
  },
  {
    path: "/ko/특허-기간-연장-터키",
    name: "특허 기간 연장 터키",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "특허 기간 연장 터키",
        component: () => import("src/pages/services/PatentAnnuityPage.vue")
      }
    ]
  },
  {
    path: "/ko/연락처",
    name: "연락처",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "연락처",
        component: () => import("pages/ContactPage.vue")
      }
    ]
  },
  {
    path: "/ko/주문/:slug",
    name: "주문",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "주문",
        component: () => import("pages/Orders.vue")
      }
    ]
  },
]
