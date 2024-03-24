import axios from "axios";
import store from "../store";

let dhc2ApiService = axios.create({
    baseURL: import.meta.env.VITE_DH2C_SERVICE_BASE_URL,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});

dhc2ApiService.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});
dhc2ApiService.interceptors.response.use(
    function (response) {
        store.commit("isLoading", false);
        return response;
    },
    async function (error) {
        store.commit("isLoading", false);
        let jsonData = await error.response?.data?.text();
        let parsedResponse = {};
        if (jsonData) {
            parsedResponse = JSON.parse(jsonData) ?? {};
        }
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

export default dhc2ApiService;
