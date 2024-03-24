import axios from "axios";
import store from "../store";

let mailDepotApiService = axios.create({
    baseURL: import.meta.env.VITE_MAIL_DEPOT_SERVICE_URL,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});
mailDepotApiService.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});
mailDepotApiService.interceptors.response.use(
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
    async function (error) {
        store.commit("isLoading", false);
        if (
            (error.response.status === 401 || error.response.status === 419) &&
            error.config.url === "/login"
        ) {
            store.dispatch("auth/refreshToken", {
                refresh_token: localStorage.getItem("refresh_token"),
            });
        }
        let parsedResponse = await error.response.data ?? {};
        console.log(parsedResponse);
        store.dispatch(
            "showResponse",
            {
                type: "error",
                message: parsedResponse?.msg ?? {},
                errors: parsedResponse?.errors ?? {},
            },
            {
                root: true,
            }
        );

        return Promise.reject(error);
    }
);

export default mailDepotApiService;
