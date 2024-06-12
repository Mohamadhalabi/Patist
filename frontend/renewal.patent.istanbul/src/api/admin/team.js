import axios from "axios";
import { useAuthStore } from "src/stores/useAuthStore";

const api = axios.create({
    baseURL: process.env.API + "/v1",
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

export function getTeams() {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/teams")
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function createTeam(form) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .post('/teams', form)
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function getTeam(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .get("/teams/" + id, )
        .then((response) => {
            return response;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function deleteTeam(id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api
        .delete("/teams/" + id, )
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

export function addMemberToTeam(id, user_id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api.put("/teams/" + id, { user_id: user_id }).then((response) => {
        return response.data;
    }).catch((error) => {
        console.log(error);
    });
}

export function removeMemberToTeam(team_id, user_id) {
    api.defaults.headers.Authorization = `Bearer ${useAuthStore().token}`;
    return api.post("/teams/" + team_id + "/users/" + user_id + "/delete").then((response) => {
        console.log(user_id)
        return response.data;
    }).catch((error) => {
        console.log(error);
    });
}