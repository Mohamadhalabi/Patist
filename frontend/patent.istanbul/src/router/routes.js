const routes = [{
        path: "/",
        name: "home",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "home",
            component: () =>
                import ("pages/IndexPage.vue")
        }],
    },
    {
        path: "/en",
        name: "homeEn",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "homeEn",
            component: () =>
                import ("pages/IndexPage.vue")
        }],
    },
    {
        path: '/login',
        name: 'login',
        component: () =>
            import ('pages/auth/Login.vue'),
        children: [{
            path: "",
            name: 'login',
            component: () =>
                import ("pages/auth/Login.vue")
        }],
    },
    {
        path: '/about',
        name: 'about',
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "about",
            component: () =>
                import ("src/pages/About.vue")
        }],
    },
    {
        path: '/api',
        name: 'api',
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "api",
            component: () =>
                import ("src/pages/API.vue")
        }],
    },
    {
        path: '/services',
        name: 'services',
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "services",
            component: () =>
                import ("pages/Services.vue")
        }],
    },
    {
        path: '/knowledge-base',
        name: 'knowledge-base',
        props: true,
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "knowledge-base",
            component: () =>
                import ("src/pages/blog/Index.vue")
        }],
    },
    {
        path: '/knowledge-base/:category',
        name: 'category',
        props: true,
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "category",
            component: () =>
                import ("src/pages/blog/Category.vue")
        }],
    },
    {
        path: '/knowledge-base/:category/:slug',
        name: 'show',
        props: true,
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "show",
            component: () =>
                import ("src/pages/blog/Show.vue")
        }],
    },
    {
        path: '/FAQs',
        name: 'FAQs',
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "FAQs",
            component: () =>
                import ("src/pages/FAQs.vue")
        }],
    },
    {
        path: "/european-patent-validation-in-turkey",
        redirect: "/european-patent-validation-in-turkiye",
    },

    {
        path: "/european-patent-validation-in-turkiye",
        name: "european patent validation in turkiye",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "european patent validation in turkiye",
            component: () =>
                import ("src/pages/services/EPValidationPage.vue")
        }],
    },
    {
        path: "/pct-national-phase-entry-in-turkey",
        redirect: "/pct-national-phase-entry-in-turkiye",
    },
    {
        path: "/pct-national-phase-entry-in-turkiye",
        name: "pct national phase entry in turkiye",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "pct national phase entry in turkiye",
            component: () =>
                import ("src/pages/services/PCTEntryPage.vue")
        }],
    },
    {
        path: "/patent-renewals-in-turkey",
        redirect: "/patent-renewals-in-turkiye",
    },
    {
        path: "/patent-renewals-in-turkiye",
        name: "patent renewals in turkiye",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "patent renewals in turkiye",
            component: () =>
                import ("src/pages/services/PatentAnnuityPage.vue")
        }],
    },
    {
        path: "/contact",
        name: "contact",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "contact",
            component: () =>
                import ("pages/ContactPage.vue")
        }],
    },
    {
        path: "/order/:slug",
        name: "Order",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "Order",
            component: () =>
                import ("pages/Orders.vue")
        }],
    },


    {
        path: "/sitemap.xml",
        name: "Sitemap",
    },

    // KB Redirect
    {
        path: "/knowledge-base/european-patent-validation-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/is-it-possible-to-validate-a-european-patent-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/is-it-possible-to-validate-a-european-patent-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/is-the-translation-of-the-description-required-for-european-patent-validations-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/is-the-translation-of-the-description-required-for-european-patent-validations-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/what-is-the-period-for-filing-the-translation-for-a-validation-in-turkey-after-the-grant-of-a-european-patent",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/what-is-the-period-for-filing-the-translation-of-a-european-patent-validation-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/what-are-the-official-fees-payable-in-european-patent-validations-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/what-are-the-official-fees-for-european-patent-validations-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/is-turkey-a-contracting-state-to-the-london-agreement",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/is-turkiye-a-contracting-state-to-the-london-agreement",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/is-a-national-patent-attorney-required-to-be-a-representative-in-european-patent-validations-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/must-a-national-professional-representative-be-appointed-for-european-patent-validations-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/how-much-a-validation-of-a-european-patent-cost-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/how-much-does-a-validation-of-a-european-patent-cost-in-turkiye",
    },
    {
        path: "/knowledge-base/european-patent-validation-in-turkey/how-long-does-it-take-to-validate-a-european-patent-in-turkey",
        redirect: "/knowledge-base/european-patent-validation-in-turkiye/how-long-does-it-take-to-validate-a-european-patent-in-turkiye",
    },


    {
        path: "/:catchAll(.*)*",
        component: () =>
            import ("pages/ErrorNotFound.vue"),
    },
];

export default routes;