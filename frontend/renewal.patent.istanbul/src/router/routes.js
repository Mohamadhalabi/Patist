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
        path: "/login",
        name: "login",
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            component: () =>
                import ("pages/auth/Login.vue")
        }, ],
        props: (route) => ({
            email_account: route.query.email_account,
            status: route.query.status
        }),
        meta: {
            requiresAuth: false,
        },
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
        path: "/dashboard/renewals",
        name: "Renewals",
        component: () =>
            import ("layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "Renewals",
            component: () =>
                import ("pages/dashboard/renewal/Index.vue")
        }],
        meta: {
            requiresAuth: true,
            allowedRoles: ['proust-admin', 'proust-user', 'client-admin', 'client-user']
        },
    },
    {
        path: "/dashboard",
        name: "IndexDashboard",
        component: () =>
            import ("layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "IndexDashboard",
            component: () =>
                import ("pages/dashboard/renewal/Index.vue")
        }],
        meta: {
            requiresAuth: false,
            allowedRoles: ['proust-admin', 'proust-user']
        },
    },

    {
        path: "/dashboard/new-application",
        name: "AddRenewal",
        component: () =>
            import ("layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "AddRenewal",
            component: () =>
                import ("pages/dashboard/renewal/Add.vue")
        }],
        meta: {
            requiresAuth: false,
            allowedRoles: ['proust-admin', 'proust-user', 'client-admin', 'client-user']
        },
    },

    {
        path: "/dashboard/renewal/:id",
        name: "ShowRenewal",
        component: () =>
            import ("layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "ShowRenewal",
            component: () =>
                import ("pages/dashboard/renewal/Show.vue")
        }, ],
        meta: {
            requiresAuth: false,
            allowedRoles: ['proust-admin', 'proust-user', 'client-admin', 'client-user']
        },
    },

    {
        path: "/dashboard/profile",
        name: "Profile",
        component: () =>
            import ("layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "Profile",
            component: () =>
                import ("pages/dashboard/renewal/Profile.vue")
        }, ],
        meta: {
            requiresAuth: true,
            allowedRoles: ['proust-admin', 'proust-user', 'client-admin', 'client-user']
        },
    },
    {
        path: "/about-us",
        name: "RenewalAbout",
        component: () =>
            import ("src/layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "RenewalAbout",
            component: () =>
                import ("pages/About.vue")
        }, ],
    },

    {
        path: "/login",
        name: "RenewalLogin",
        component: () =>
            import ("src/layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "RenewalLogin",
            component: () =>
                import ("pages/auth/Login.vue")
        }, ],
    },

    {
        path: "/register",
        name: "RenewalRegister",
        component: () =>
            import ("src/layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "RenewalRegister",
            component: () =>
                import ("pages/auth/Register.vue")
        }, ],
    },

    {
        path: "/reset-password",
        name: "RenewalResetPassword",
        component: () =>
            import ("src/layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "RenewalResetPassword",
            component: () =>
                import ("pages/auth/ResetPasswordPage.vue")
        }, ],
    },

    {
        path: "/create-password",
        name: "RenewalCreatePassword",
        component: () =>
            import ("src/layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "RenewalCreatePassword",
            component: () =>
                import ("pages/auth/CreatePasswordPage.vue")
        }, ],
    },

    {
        path: "/sitemap.xml",
        name: "Sitemap",
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
        path: '/knowledge-base/:slug',
        name: '',
        props: true,
        component: () =>
            import ("layouts/MainLayout.vue"),
        children: [{
            path: "",
            name: "knowledge-base.show",
            component: () =>
                import ("src/pages/blog/Show.vue")
        }],
    },

    // Dashboard/Admin Routes
    {
        path: "/dashboard/admin",
        name: "AdminIndex",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "AdminIndex",
            component: () =>
                import ("pages/dashboard/admin/Index.vue")
        }, ],
    },

    // User Details
    {
        path: "/dashboard/admin/user/:id",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "AdminUserDetails",
            component: () =>
                import ("pages/dashboard/admin/user/Index.vue")
        }, ],
    },

    // Add Reminder
    {
        path: "/dashboard/admin/reminder/add",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "AddAdminReminder",
            component: () =>
                import ("pages/dashboard/admin/reminder/Add.vue")
        }, ],
    },
    // Add Reminder
    {
        path: "/dashboard/admin/reminders",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "IndexAdminReminder",
            component: () =>
                import ("pages/dashboard/admin/reminder/Index.vue")
        }, ],
    },

    // Reminder Details
    {
        path: "/dashboard/admin/reminder/:id",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "AdminReminderDetails",
            component: () =>
                import ("pages/dashboard/admin/reminder/Show.vue")
        }, ],
    },
    // Reminder Details
    {
        path: "/dashboard/admin/custom-reminder/:id",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "AdminCustomReminderDetails",
            component: () =>
                import ("pages/dashboard/admin/reminder/CustomReminderShow.vue")
        }, ],
    },

    // Reminder Details
    {
        path: "/dashboard/contact",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "ContactPage",
            component: () =>
                import ("pages/dashboard/Contact.vue")
        }, ],
    },


    /**
    Team Routes
    */
    // Team List
    {
        path: "/dashboard/admin/teams",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "TeamIndex",
            component: () =>
                import ("pages/dashboard/admin/team/Index.vue")
        }, ],
    },

    // Team Details
    {
        path: "/dashboard/admin/teams/:id",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "TeamShow",
            component: () =>
                import ("pages/dashboard/admin/team/Show.vue")
        }, ],
    },

    // Team Details
    {
        path: "/dashboard/admin/failed-requests",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "TeamShow",
            component: () =>
                import ("pages/dashboard/admin/failedRequest/Index.vue")
        }, ],
    },
    /**
    End Team Routes
    */

    // Company List
    {
        path: "/dashboard/admin/companies",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "CompanyIndex",
            component: () =>
                import ("pages/dashboard/admin/company/Index.vue")
        }, ],
    },
    // Company Details
    {
        path: "/dashboard/admin/company/:id",
        name: "",
        component: () =>
            import ("src/layouts/DashboardLayout.vue"),
        children: [{
            path: "",
            name: "CompanyShow",
            component: () =>
                import ("pages/dashboard/admin/company/Show.vue")
        }, ],
    },
    /**
    End Team Routes
    */
    {
        path: "/:catchAll(.*)*",
        component: () =>
            import ("pages/ErrorNotFound.vue"),
    },
];

export default routes;