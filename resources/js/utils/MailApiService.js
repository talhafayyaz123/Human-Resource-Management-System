import axios from "axios";
import store from "../store";

let licenseApiService = axios.create({
    baseURL: import.meta.env.VITE_MAIL_SERVICE_URL ?? '',
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});
licenseApiService.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});
licenseApiService.interceptors.response.use(
    function (response) {
        store.commit("isLoading", false);
        return response;
    },
    async function (error) {
        store.commit("isLoading", false);
        let parsedResponse = JSON.parse(await error.response.data.text()) ?? {};
        store.dispatch(
            "showResponse",
            {
                type: "error",
                message: parsedResponse?.message ?? {},
                errors: parsedResponse?.errors ?? {},
            },
            {
                root: true,
            }
        );

        return Promise.reject(error);
    }
);

export default licenseApiService;
