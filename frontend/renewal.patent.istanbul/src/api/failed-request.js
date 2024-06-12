import axios from "axios";
import { useAuthStore } from "src/stores/useAuthStore";

const api = axios.create({
    baseURL: process.env.API + "/v1/",
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

export function getFailedRequests() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/failed-requests")
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function saveFailedRequest(form) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api.post("/failed-requests", form).then((response) => {
        return response.data;
    }).catch((error) => {
        console.log(error);
    });
}

export function deleteFailedRequest(patentNumber) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .delete("/failed-requests/" + patentNumber)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}