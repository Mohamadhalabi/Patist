import { route } from 'quasar/wrappers'
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router'
import routes from './routes'
import routeEs from './routes-es' // Import the route file for the Spanish language
import routeFr from './routes-fr' // Import the route file for the French language
import routeIt from './routes-it' // Import the route file for the Italian language
import routesDe from "src/router/routes-de";
import routeTr from "src/router/route-tr";
import routeKo from "./routes-ko"
import routeJa from "./routes-ja"
// Import other route files for different languages as needed

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory)

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes: [...routes, ...routeEs, ...routeTr, ...routesDe, ...routeFr, ...routeIt, ...routeKo , ...routeJa], // Combine the routes from different language files
    history: createHistory(process.env.MODE === 'ssr' ? void 0 : process.env.VUE_ROUTER_BASE)
  })

  return Router
})
