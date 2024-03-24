import axios from "axios";
import store from "../store";

let authApiService = axios.create({
    baseURL: import.meta.env.VITE_AUTH_SERVICE_URL,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});

authApiService.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});
authApiService.interceptors.response.use(
    function (response) {
        store.commit("isLoading", false);
        if (
            response?.status == 200 &&
            (response?.config?.url === "/login" ||
                response?.config?.url === "/refresh-token")
        ) {
            localStorage.setItem("token", response?.data?.token);
            localStorage.setItem(
                "refresh_token",
                response?.data?.refresh_token
            );
            localStorage.setItem(
                "roles",
                JSON.stringify(response?.data?.token_info?.roles ?? [])
            );
            localStorage.setItem(
                "permissions",
                JSON.stringify(response?.data?.token_info?.permissions ?? [])
            );
            localStorage.setItem(
                "scopes",
                JSON.stringify(response?.data?.token_info?.scopes ?? [])
            );
            localStorage.setItem(
                "user_id",
                response?.data?.token_info?.user_id
            );
            localStorage.setItem(
                "types",
                JSON.stringify(response?.data?.token_info?.types ?? {})
            );
        }
        return response;
    },
    function (error) {
        store.commit("isLoading", false);
        if (
            (error.response.status === 401 || error.response.status === 419) &&
            error.config.url === "/login"
        ) {
            store.dispatch("auth/refreshToken", {
                refresh_token: localStorage.getItem("refresh_token"),
            });
        }
        store.dispatch(
            "showResponse",
            {
                type: "error",
                message: error?.response?.data?.msg ?? {},
                errors: error?.response?.data?.errors ?? {},
            },
            {
                root: true,
            }
        );

        return Promise.reject(error);
    }
);

export default authApiService;
