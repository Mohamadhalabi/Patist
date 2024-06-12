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

export function paymentSingle(data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post("/paymentOne", { renewal_id: data })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function paymentAll() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post("/paymentAll")
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function completeRenewal(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .put(`/renewals/${id}`, {
            renewal_id: id,
            status: "approved",
            type: "complete-renewal"
        })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}
export function completePayment(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .put(`/renewals/${id}`, {
            renewal_id: id,
            type: "complete-payment"
        })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}
export function adminApprove(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .put(`/renewals/${id}`, {
            renewal_id: id,
            type: "admin-approve",
            value: true
        })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}