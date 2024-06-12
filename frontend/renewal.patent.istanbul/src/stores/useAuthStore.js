import { defineStore } from "pinia";

export const useAuthStore = defineStore("user", {
    state: () => ({
        token: null,
        user: {
            id: null,
            name: null,
            email: null,
            email_verified_at: null,
            is_verified: null,
            timezone: null,
            phone: null,
            address: null,
            city: null,
            country: null,
            state: null,
            first_register: null,
            role: null,
            team_id: null,
            username: null,
            verification_code: null,
            use_calendar_password: null,
            calendar_password: null,
            created_at: null,
            updated_at: null
        },
    }),
    getters: {
        isAuthenticated() {
            return (this.token !== null) ? true : false;
        },
        loggedInUser() {
            return this.user;
        },
    },
    actions: {
        setToken(token) {
            this.token = token;
        },
        login(token, user) {
            this.token = token;
            this.user = user;
        },
        logout() {
            this.token = null;
            this.user = null;
        },
    },
    persist: true
});