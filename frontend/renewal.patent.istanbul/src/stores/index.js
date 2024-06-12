import { store } from "quasar/wrappers"
import { createPinia } from "pinia"
import { createPersistedState } from "pinia-plugin-persistedstate"
import { Cookies } from "quasar"

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default store(({ ssrContext }) => {
  const pinia = createPinia()
  const cookies = process.env.SERVER ? Cookies.parseSSR(ssrContext) : Cookies

  const cookiesStorage = {
    getItem: key => {
      return JSON.stringify(cookies.get(key))
    },
    setItem: (key, value) => {
      cookies.set(key, JSON.parse(value), {
        path: "/",
        sameSite: "Lax",
        secure: !process.env.DEV,
        expires: 7
      })
    }
  }

  const persistedState = createPersistedState({
    storage: cookiesStorage
  })
  pinia.use(persistedState)

  // You can add Pinia plugins here
  // pinia.use(SomePiniaPlugin)

  return pinia
})
