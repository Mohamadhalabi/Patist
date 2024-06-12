import axios from "axios";
import { useAuthStore } from "src/stores/useAuthStore";

const api = axios.create({
    baseURL: process.env.API + "/v1/renewals/",
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

export function customReminders() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/custom-reminders")
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function getReminder(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get(`/custom-reminders/${id}`)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}
export function updateReminder(id, data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .put(`/custom-reminders/${id}`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function addReminder(data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post(`/custom-reminders`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}


export function getReminderDates(data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post(`/custom-reminders/get-reminder-dates`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}
export function getFields(data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post(`/custom-reminders/get-fields`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}