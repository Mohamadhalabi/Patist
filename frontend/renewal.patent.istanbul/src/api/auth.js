import axios from "axios";
import { useAuthStore } from "src/stores/useAuthStore";
import { storeToRefs } from "pinia";

const api = axios.create({
    baseURL: process.env.API,
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

api.interceptors.request.use(
    (config) => {
        const token = useAuthStore().token;
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            useAuthStore().logout();
        }
        return Promise.reject(error);
    }
);

export function login(email, password) {
    return api.post("/login", { email, password }).then((response) => {
        if (response.data.success != false) {

            const authStore = useAuthStore();
            const { login } = authStore;
            if (response.data.data.token != null) {
                login(response.data.data.token, response.data.data.user)
            } else {
                console.log("token null")
            }
            let token = response.data.data.token;
            let user = response.data.data.user;
            login(token, {
                id: user.id,
                name: user.name,
                email: user.email,
                is_verified: user.is_verified,
                phone: user.phone,
                address: user.address,
                city: user.city,
                country: user.country,
                state: user.state,
                role: user.role,
                timezone: user.timezone,
                use_calendar_password: user.use_calendar_password,
                calendar_password: user.calendar_password,
                created_at: user.created_at,
            })
        }
        return response.data;
    });
}

export function loginAfterEmailValidation(token, email) {
    return api.post("/loginAfterEmailValidation", { token, email }).then((response) => {
        if (response.data.success != false) {
            const token = response.data.data.token;
            const user = response.data.data.user;
            useAuthStore().login(token, user);
        }
        return response.data;
    });
}

export function register(name, email, password, phone) {
    return api
        .post("/register", { name, email, password, phone })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}


export function resend(email) {
    console.log(email)
    return api.post("/email/resend", { email }).then((response) => {
        return response.data;
    });
}

export function resetPassword(email) {
    return api.post("/password/email", { email }).then((response) => {
        return response.data;
    });
}

export function resetPasswordReset(password, password_confirmation, token) {
    return api.post("/password/reset", { password, password_confirmation, token }).then((response) => {
        return response.data;
    });
}

export function verifyFirstRegister(token, password) {
    return api.post("/verify-first-register", { token, password }).then((response) => {
        return response.data;
    });
}