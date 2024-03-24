/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
import store from "./store";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import router from "./router";

/** adding interceptors */

window.axios.interceptors.response.use(
    (response) => {
        return response;
    },
    function (error) {
        const requestUrl = error.config ? error.config.url : null;
        if (
            requestUrl != null &&
            requestUrl.includes("http://elo.docshero.de:9020")
        ) {
            store.dispatch(
                "showResponse",
                {
                    type: "error",
                    message: error?.response?.data?.message ?? {},
                    errors: error?.response?.data?.errors ?? {},
                },
                {
                    root: true,
                }
            );
            return Promise.reject(error.response);
        }
        if (error.response.status === 401 || error.response.status === 419) {
            localStorage.setItem("user", {});
            localStorage.setItem("authenticated", false);
            router.push({ name: "Login" });
        }
        return Promise.reject(error.response);
    }
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
