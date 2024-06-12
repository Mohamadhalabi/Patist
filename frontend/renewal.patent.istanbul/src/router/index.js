import {
    route
} from 'quasar/wrappers'
import {
    createRouter,
    createMemoryHistory,
    createWebHistory,
    createWebHashHistory
} from 'vue-router'
import routes from './routes'
import {
    Notify
} from 'quasar';
import {
    useAuthStore
} from "src/stores/useAuthStore";


export default route(function( /* { store, ssrContext } */ ) {
    const createHistory = process.env.SERVER ?
        createMemoryHistory :
        (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory)

    const Router = createRouter({
        scrollBehavior: () => ({
            left: 0,
            top: 0
        }),
        routes: [...routes], // Combine the routes from different language files
        history: createHistory(process.env.MODE === 'ssr' ? void 0 : process.env.VUE_ROUTER_BASE)
    })

    // eğer useAuthStore().isAuthenticated değeri false ise ve route.name /dashboard içeriyorsa login sayfasına yönlendir
    Router.beforeEach((to, from, next) => {
        if (to.matched.some(record => record.meta.requiresAuth)) {
            if (!useAuthStore().isAuthenticated) {
                next({
                    path: '/login',
                    query: {
                        redirect: to.fullPath
                    }
                })
            } else {
                next()
            }
        } else {
            next()
        }

        const user = getCurrentUser();

        // If the user is logged in
        if (user) {
            // Check if the page requires authentication
            if (to.meta.requiresAuth) {
                // Get the user's role
                const userRole = user.role;
                // Is the user's role among the allowed roles for the page?
                if (to.meta.allowedRoles.includes(userRole)) {
                    // The user has one of the allowed roles, grant access to the page
                    next();
                } else {
                    // The user doesn't have permission to access this page, show an error
                    Notify.create({
                        type: 'negative',
                        message: 'You do not have permission to access this page.',
                    });
                    next('/'); // Redirect to the home page
                }
            } else {
                // The page does not require authentication, allow access
                next();
            }
        } else {
            // If the user is not logged in, redirect to the login page
            next('/login');
        }
    })

    return Router
})