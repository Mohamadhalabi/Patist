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

export function getRenewals() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/renewals")
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function getRenewal(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get(`/renewals/${id}`)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function search(patentNumber) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post("/search", { patentNumber })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function saveRenewal(form) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api.post("/renewals", form).then((response) => {
        return response.data;
    }).catch((error) => {
        console.log(error);
    });
}

export function searchApplicationNo(patentNumber) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post("/search-application-no", { patentNumber })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function saveNewRenewalRequest(patentNumber) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post("/save-renewal-request", { patentNumber })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}