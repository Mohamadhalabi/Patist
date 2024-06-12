import { boot } from 'quasar/wrappers'
import axios from 'axios'

// API URL
const url = (process.env.DEV) ? 'http://127.0.0.1:8000' : process.env.API_URL;

const api = axios.create({ baseURL: url })

export default boot(({ app }) => {
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})
export { axios, api }
