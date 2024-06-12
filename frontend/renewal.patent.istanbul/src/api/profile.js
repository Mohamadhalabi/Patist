import axios from "axios";
import { useAuthStore } from "src/stores/useAuthStore";

const api = axios.create({
    baseURL: process.env.API + "/v1/profile/",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

// Request interceptor to attach token to requests
api.interceptors.request.use((config) => {
    const authStore = useAuthStore();
    const token = authStore.token;
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Response interceptor to handle token expiration errors
api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response.status === 401) {
            const authStore = useAuthStore();
            authStore.logout();
            router.push("/login");
        }
        return Promise.reject(error);
    }
);

export function updateProfile(data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post("/update", { profile: data })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function getTimeZones() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/timezones")
        .then((response) => {
            return response;
        })
        .catch((error) => {
            console.log(error);
        });
}