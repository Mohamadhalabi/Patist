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

export function reminders() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/reminders")
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
        .get(`/reminders/${id}`)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}
export function getCalendar() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get(`/calendar`)
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
        .put(`/reminders/${id}`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function updateEmailSetting(id, data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .put(`/reminders/${id}`, data)
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
        .post(`/reminders`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function updateReminderStatus(id, status) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .put(`/reminders/${id}`, status)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function deleteReminder(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .delete(`/reminders/${id}`)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function addReminderFragment(reminder_id, data) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post(`/reminders/${reminder_id}/fragment/add`, data)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function deleteReminderFragment(reminder_id, fragment_id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post(`/reminders/${reminder_id}/fragment/${fragment_id}/delete`)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}